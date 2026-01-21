<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        
        // Campos de Valor
        'total_amount', // Adicionado para compatibilidade com o Controller
        'subtotal',
        'shipping_cost',
        'total', // Mantido caso seu banco use este nome
        
        // Campos de Endereço e Cliente
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_number',
        'shipping_complement',
        'shipping_neighborhood',
        'shipping_city',
        'shipping_state',
        'shipping_zipcode',
        'document', // Adicionado caso precise salvar CPF
        
        // Detalhes do Envio
        'shipping_method',
        'shipping_days',
        'tracking_code',
        
        // Pagamento
        'payment_method',
        'payment_id',
        'payment_status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'total_amount' => 'decimal:2', // Adicionado
        'paid_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // --- Helpers de Status ---

    public function isPending()
    {
        return $this->status === 'pending_payment' || $this->status === 'pending';
    }

    public function isPaid()
    {
        return in_array($this->status, ['paid', 'processing', 'shipped', 'delivered']);
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending_payment', 'pending', 'paid', 'processing']);
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'payment_status' => 'approved',
            'paid_at' => now(),
        ]);
    }

    public function markAsCancelled()
    {
        if ($this->canBeCancelled()) {
            $this->update(['status' => 'cancelled']);
            
            // Devolver estoque se a relação existir e estiver carregada ou acessível
            if ($this->items) {
                foreach ($this->items as $item) {
                    if ($item->productVariant) {
                        $item->productVariant->incrementStock($item->quantity);
                    }
                }
            }
        }
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendente',
            'pending_payment' => 'Aguardando Pagamento',
            'paid' => 'Pago',
            'processing' => 'Em Processamento',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            default => 'Desconhecido',
        };
    }
}