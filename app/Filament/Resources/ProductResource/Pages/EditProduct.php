<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Garante que stock_quantity e sizes sejam tratados corretamente
        $data['stock_quantity'] = isset($data['stock_quantity']) ? (int) $data['stock_quantity'] : 0;
        $data['sizes'] = $data['sizes'] ?? [];
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Sincroniza o estoque com as variantes após salvar
        $this->record->syncSimpleStockAndSizes();
    }
}
