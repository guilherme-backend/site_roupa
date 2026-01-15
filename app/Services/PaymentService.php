<?php

namespace App\Services;

use MercadoPago\SDK;
use MercadoPago\Payment;
use MercadoPago\Preference;
use MercadoPago\Item;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        SDK::setAccessToken(config('services.mercadopago.access_token'));
    }

    /**
     * Cria uma preferência de pagamento no Mercado Pago
     */
    public function createPreference(array $orderData): array
    {
        try {
            $preference = new Preference();
            
            // Itens do pedido
            $items = [];
            foreach ($orderData['items'] as $item) {
                $mpItem = new Item();
                $mpItem->title = $item['product_name'];
                $mpItem->quantity = $item['quantity'];
                $mpItem->unit_price = (float) $item['price'];
                $items[] = $mpItem;
            }
            
            // Adicionar frete como item
            if ($orderData['shipping_cost'] > 0) {
                $shippingItem = new Item();
                $shippingItem->title = 'Frete - ' . $orderData['shipping_method'];
                $shippingItem->quantity = 1;
                $shippingItem->unit_price = (float) $orderData['shipping_cost'];
                $items[] = $shippingItem;
            }
            
            $preference->items = $items;
            
            // Informações do comprador
            $preference->payer = [
                'name' => $orderData['customer_name'],
                'email' => $orderData['customer_email'],
                'phone' => [
                    'number' => $orderData['customer_phone'],
                ],
            ];
            
            // URLs de retorno
            $preference->back_urls = [
                'success' => route('checkout.success', ['order' => $orderData['order_id']]),
                'failure' => route('checkout.index'),
                'pending' => route('checkout.success', ['order' => $orderData['order_id']]),
            ];
            
            $preference->auto_return = 'approved';
            
            // Notificação de webhook
            $preference->notification_url = route('webhook.mercadopago');
            
            // Referência externa
            $preference->external_reference = $orderData['order_number'];
            
            // Métodos de pagamento
            $preference->payment_methods = [
                'excluded_payment_types' => [],
                'installments' => 12,
            ];
            
            $preference->save();
            
            return [
                'success' => true,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao criar preferência Mercado Pago: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro ao processar pagamento. Tente novamente.',
            ];
        }
    }

    /**
     * Processa notificação de webhook do Mercado Pago
     */
    public function processWebhook(array $data): array
    {
        try {
            if (!isset($data['type']) || $data['type'] !== 'payment') {
                return ['success' => false, 'message' => 'Tipo de notificação não suportado'];
            }
            
            $paymentId = $data['data']['id'] ?? null;
            
            if (!$paymentId) {
                return ['success' => false, 'message' => 'ID de pagamento não encontrado'];
            }
            
            $payment = Payment::find_by_id($paymentId);
            
            if (!$payment) {
                return ['success' => false, 'message' => 'Pagamento não encontrado'];
            }
            
            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'external_reference' => $payment->external_reference,
                'transaction_amount' => $payment->transaction_amount,
                'payment_method_id' => $payment->payment_method_id,
                'payment_type_id' => $payment->payment_type_id,
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook Mercado Pago: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro ao processar notificação',
            ];
        }
    }

    /**
     * Obtém informações de um pagamento
     */
    public function getPaymentInfo(string $paymentId): array
    {
        try {
            $payment = Payment::find_by_id($paymentId);
            
            if (!$payment) {
                return ['success' => false, 'message' => 'Pagamento não encontrado'];
            }
            
            return [
                'success' => true,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'amount' => $payment->transaction_amount,
                'payment_method' => $payment->payment_method_id,
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao obter informações de pagamento: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro ao consultar pagamento',
            ];
        }
    }
}
