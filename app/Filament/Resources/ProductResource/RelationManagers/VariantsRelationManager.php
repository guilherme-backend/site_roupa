<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Variações';
    
    protected static ?string $modelLabel = 'Variação';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('size')
                            ->label('Tamanho')
                            ->options([
                                'PP' => 'PP',
                                'P' => 'P',
                                'M' => 'M',
                                'G' => 'G',
                                'GG' => 'GG',
                                'XG' => 'XG',
                                'XGG' => 'XGG',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('color')
                            ->label('Cor')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\ColorPicker::make('color_hex')
                            ->label('Código da Cor')
                            ->helperText('Selecione a cor para visualização'),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price_adjustment')
                            ->label('Ajuste de Preço')
                            ->numeric()
                            ->default(0)
                            ->prefix('R$')
                            ->step(0.01)
                            ->helperText('Valor a adicionar ou subtrair do preço base'),
                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('Quantidade em Estoque')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Forms\Components\Toggle::make('is_available')
                            ->label('Disponível')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('size')
                    ->label('Tamanho')
                    ->sortable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Cor')
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color_hex')
                    ->label('Cor'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_adjustment')
                    ->label('Ajuste')
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Estoque')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Disponível')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
