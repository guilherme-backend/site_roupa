<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Itens do Pedido';
    
    protected static ?string $modelLabel = 'Item';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')
                    ->label('Produto')
                    ->disabled(),
                Forms\Components\TextInput::make('variant_size')
                    ->label('Tamanho')
                    ->disabled(),
                Forms\Components\TextInput::make('variant_color')
                    ->label('Cor')
                    ->disabled(),
                Forms\Components\TextInput::make('price')
                    ->label('Preço')
                    ->disabled()
                    ->prefix('R$'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantidade')
                    ->disabled(),
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->disabled()
                    ->prefix('R$'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Produto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('variant_size')
                    ->label('Tamanho'),
                Tables\Columns\TextColumn::make('variant_color')
                    ->label('Cor'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Preço')
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qtd'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('BRL'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Não permitir criar itens manualmente
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
