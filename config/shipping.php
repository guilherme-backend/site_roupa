<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CEP de Origem
    |--------------------------------------------------------------------------
    |
    | CEP de onde os produtos serão enviados
    |
    */
    'origin_zipcode' => env('SHIPPING_ORIGIN_ZIPCODE', '01310100'),
    
    /*
    |--------------------------------------------------------------------------
    | Peso Padrão (kg)
    |--------------------------------------------------------------------------
    |
    | Peso padrão para cálculo de frete quando não especificado
    |
    */
    'default_weight' => env('SHIPPING_DEFAULT_WEIGHT', 0.5),
];
