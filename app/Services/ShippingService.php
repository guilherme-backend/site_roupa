<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $cepOrigin;
    
    public function __construct()
    {
        // CEP de origem configurável no .env
        $this->cepOrigin = config('shipping.origin_zipcode', '01310100');
    }

    /**
     * Calcula o frete usando a API dos Correios
     */
    public function calculateShipping(string $cepDestination, float $weight = 0.5): array
    {
        // Remove formatação do CEP
        $cepDestination = preg_replace('/[^0-9]/', '', $cepDestination);
        
        if (strlen($cepDestination) !== 8) {
            return [
                'success' => false,
                'message' => 'CEP inválido',
            ];
        }

        try {
            // Simulação de cálculo de frete
            // Em produção, integrar com API real dos Correios ou Melhor Envio
            $options = $this->simulateShipping($cepDestination, $weight);
            
            return [
                'success' => true,
                'options' => $options,
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao calcular frete: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro ao calcular frete. Tente novamente.',
            ];
        }
    }

    /**
     * Simula cálculo de frete (substituir por integração real)
     */
    protected function simulateShipping(string $cep, float $weight): array
    {
        // Simulação baseada na região (primeiros dígitos do CEP)
        $region = (int) substr($cep, 0, 2);
        
        // Frete base
        $basePAC = 15.00;
        $baseSEDEX = 25.00;
        
        // Ajuste por região
        if ($region >= 10 && $region <= 19) {
            // Sudeste
            $multiplier = 1.0;
        } elseif ($region >= 20 && $region <= 28) {
            // Sul
            $multiplier = 1.3;
        } elseif ($region >= 40 && $region <= 48) {
            // Nordeste
            $multiplier = 1.5;
        } elseif ($region >= 50 && $region <= 59) {
            // Centro-Oeste
            $multiplier = 1.4;
        } elseif ($region >= 60 && $region <= 69) {
            // Norte
            $multiplier = 1.8;
        } else {
            $multiplier = 1.2;
        }
        
        // Ajuste por peso
        $weightMultiplier = 1 + ($weight * 0.2);
        
        return [
            [
                'name' => 'PAC',
                'price' => round($basePAC * $multiplier * $weightMultiplier, 2),
                'days' => rand(7, 12),
                'code' => '04510',
            ],
            [
                'name' => 'SEDEX',
                'price' => round($baseSEDEX * $multiplier * $weightMultiplier, 2),
                'days' => rand(2, 5),
                'code' => '04014',
            ],
        ];
    }

    /**
     * Valida CEP usando API ViaCEP
     */
    public function validateCep(string $cep): array
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            return [
                'success' => false,
                'message' => 'CEP inválido',
            ];
        }

        try {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['erro'])) {
                    return [
                        'success' => false,
                        'message' => 'CEP não encontrado',
                    ];
                }
                
                return [
                    'success' => true,
                    'data' => [
                        'zipcode' => $data['cep'],
                        'street' => $data['logradouro'] ?? '',
                        'neighborhood' => $data['bairro'] ?? '',
                        'city' => $data['localidade'] ?? '',
                        'state' => $data['uf'] ?? '',
                    ],
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Erro ao consultar CEP',
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao validar CEP: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro ao consultar CEP',
            ];
        }
    }
}
