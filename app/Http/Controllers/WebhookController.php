<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Recebe a notificação do Mercado Pago
        // O MP envia um POST com dados como 'type' e 'data.id'
        $data = $request->all();
        
        Log::info('Webhook Recebido:', $data);

        // Verifica se é uma notificação de pagamento
        if (!isset($data['type']) || $data['type'] !== 'payment') {
            // Em algumas versões da API, o type pode vir como 'topic' => 'payment'
            // Mas vamos focar no padrão atual. Se não for pagamento, ignoramos.
            if(isset($data['action']) && $data['action'] == 'payment.created') {
                // Segue o fluxo
            } else {
                return response()->json(['status' => 'ignored'], 200);
            }
        }

        try {
            // 2. Configura o Token (caso não esteja globalmente carregado)
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

            // 3. Busca os detalhes do pagamento na API do Mercado Pago
            $paymentId = $data['data']['id'] ?? $data['id'] ?? null;
            
            if (!$paymentId) {
                return response()->json(['error' => 'Payment ID not found'], 400);
            }

            $client = new PaymentClient();
            $payment = $client->get($paymentId);

            // 4. Identifica o Pedido no nosso banco
            // Nós enviamos o ID do pedido no campo 'external_reference'
            $orderId = $payment->external_reference;
            
            $order = Order::find($orderId);

            if (!$order) {
                Log::error("Pedido não encontrado para o pagamento ID: $paymentId | Order ID: $orderId");
                return response()->json(['error' => 'Order not found'], 404);
            }

            // 5. Atualiza o Status do Pedido
            switch ($payment->status) {
                case 'approved':
                    $order->markAsPaid(); // Método que já existe no seu Model
                    // Se precisar salvar o ID do pagamento:
                    $order->payment_id = $paymentId;
                    $order->save();
                    break;

                case 'pending':
                case 'in_process':
                    $order->update(['status' => 'pending_payment']);
                    break;

                case 'rejected':
                case 'cancelled':
                    $order->markAsCancelled();
                    break;
            }

            Log::info("Pedido #{$order->id} atualizado para: {$payment->status}");

            return response()->json(['status' => 'success'], 200);

        } catch (MPApiException $e) {
            Log::error('Erro na API do Mercado Pago Webhook:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'API Error'], 500);
        } catch (\Exception $e) {
            Log::error('Erro interno no Webhook:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Error'], 500);
        }
    }
}