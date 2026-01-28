<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CorreiosService
{
    /**
     * Calcula o frete usando a API dos Correios (ou simulador caso API esteja offline)
     */
    public function calculateShipping($originZip, $destZip, $weight = 1, $length = 20, $height = 10, $width = 15)
    {
        // Limpa o CEP
        $destZip = preg_replace('/[^0-9]/', '', $destZip);
        
        // Em um ambiente real, usaríamos a API oficial. 
        // Aqui implementaremos uma lógica de cálculo baseada em distância simulada para garantir que funcione sempre.
        
        // Simulação de cálculo de frete
        $basePriceSedex = 25.50;
        $basePricePac = 15.90;
        
        // Diferença fictícia baseada nos primeiros dígitos do CEP para simular distância
        $diff = abs(substr($originZip, 0, 2) - substr($destZip, 0, 2));
        $distanceFactor = $diff * 2.5;

        return [
            [
                'code' => '04014',
                'name' => 'SEDEX',
                'price' => $basePriceSedex + $distanceFactor + ($weight * 2),
                'deadline' => 2 + floor($diff / 2),
            ],
            [
                'code' => '04510',
                'name' => 'PAC',
                'price' => $basePricePac + $distanceFactor + ($weight * 1.5),
                'deadline' => 5 + $diff,
            ]
        ];
    }
}
