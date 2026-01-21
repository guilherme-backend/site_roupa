<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class PaymentService
{
    public function __construct()
    {
        // Autenticação
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
    }

    public function createPreference(array $orderData): array
    {
        try {
            $client = new PreferenceClient();

            // 1. Preparar itens
            $items = [];
            foreach ($orderData['items'] as $item) {
                $items[] = [
                    "title" => $item['product_name'],
                    "quantity" => intval($item['quantity']),
                    "unit_price" => floatval($item['price']),
                    "currency_id" => "BRL"
                ];
            }

            // Adicionar frete se houver
            if (isset($orderData['shipping_cost']) && $orderData['shipping_cost'] > 0) {
                $items[] = [
                    "title" => 'Frete',
                    "quantity" => 1,
                    "unit_price" => floatval($orderData['shipping_cost']),
                    "currency_id" => "BRL"
                ];
            }

            // --- LIMPEZA DE DADOS ---
            $rawDoc = $orderData['customer_document'] ?? '00000000000';
            $docClean = preg_replace('/[^0-9]/', '', $rawDoc);

            // Requisição da preferência
            $request = [
                "items" => $items,
                "payer" => [
                    "name" => $orderData['customer_name'],
                    "email" => $orderData['customer_email'],
                    "identification" => [
                        "type" => "CPF",
                        "number" => $docClean
                    ]
                ],
                "back_urls" => [
                    "success" => route('checkout.success', ['order' => $orderData['order_id']]),
                    "failure" => route('checkout.index'),
                    "pending" => route('checkout.success', ['order' => $orderData['order_id']])
                ],
                
                // CORREÇÃO AQUI: Comentei o auto_return para evitar erro em localhost
                // "auto_return" => "approved", 
                
                // URL de notificação (Webhook)
                "notification_url" => config('app.env') === 'local' 
                                        ? 'https://seusite.com/webhook/mercadopago' // Fake para não dar erro
                                        : route('webhook.mercadopago'),
                                        
                "external_reference" => (string) $orderData['order_number'],
                "statement_descriptor" => "LOJA ROUPA"
            ];

            $preference = $client->create($request);

            return [
                'success' => true,
                'id' => $preference->id,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point,
            ];

        } catch (MPApiException $e) {
            $content = $e->getApiResponse()->getContent();
            Log::error('Erro MP API:', ['content' => $content]);
            
            return [
                'success' => false, 
                'message' => 'Erro API MP: ' . $e->getMessage(),
                'details' => $content
            ];
            
        } catch (\Exception $e) {
            Log::error('Erro Geral MP: ' . $e->getMessage());
            return [
                'success' => false, 
                'message' => 'Erro Interno: ' . $e->getMessage()
            ];
        }
    }
}