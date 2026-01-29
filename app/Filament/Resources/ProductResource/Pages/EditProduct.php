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
        // Captura os dados virtuais para o objeto do modelo antes que o Filament tente salvar
        $this->record->stock_quantity = isset($data['stock_quantity']) ? (int) $data['stock_quantity'] : 0;
        $this->record->sizes = $data['sizes'] ?? [];
        
        // Remove os campos que não existem na tabela 'products' para evitar erro SQL
        unset($data['stock_quantity']);
        unset($data['sizes']);
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Agora que o produto base foi salvo, sincronizamos as variantes
        $this->record->syncSimpleStockAndSizes();
    }
}
