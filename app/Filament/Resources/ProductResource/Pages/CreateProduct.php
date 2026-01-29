<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Guardamos temporariamente os dados virtuais
        $this->stock_data = [
            'stock_quantity' => isset($data['stock_quantity']) ? (int) $data['stock_quantity'] : 0,
            'sizes' => $data['sizes'] ?? []
        ];

        // Remove os campos que não existem na tabela 'products'
        unset($data['stock_quantity']);
        unset($data['sizes']);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Atribui os dados guardados ao registro criado e sincroniza
        $this->record->stock_quantity = $this->stock_data['stock_quantity'];
        $this->record->sizes = $this->stock_data['sizes'];
        
        $this->record->syncSimpleStockAndSizes();
    }
}
