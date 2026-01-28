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

    protected static ?string $title = 'Variações de Tamanho e Cor';
    
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
                                'U' => 'Único',
                            ])
                            ->required(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->category?->has_sizes ?? true)
                            ->visible(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->category?->has_sizes ?? true),
                        
                        Forms\Components\TextInput::make('color')
                            ->label('Cor')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Preto, Azul Marinho...'),
                        
                        Forms\Components\ColorPicker::make('color_hex')
                            ->label('Código da Cor')
                            ->helperText('Opcional: Escolha a cor para mostrar no site'),
                        
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU (Código de Barras)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('price_adjustment')
                            ->label('Ajuste de Preço')
                            ->numeric()
                            ->default(0)
                            ->prefix('R$')
                            ->step(0.01)
                            ->helperText('Ex: +10.00 para tamanhos especiais'),
                        
                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('Quantidade em Estoque')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        
                        Forms\Components\Toggle::make('is_available')
                            ->label('Disponível para venda')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['product.category']))
            ->columns([
                Tables\Columns\TextColumn::make('size')
                    ->label('Tam.')
                    ->sortable()
                    ->toggleable(visible: fn (RelationManager $livewire) => $livewire->getOwnerRecord()->category?->has_sizes ?? true),
                
                Tables\Columns\TextColumn::make('color')
                    ->label('Cor')
                    ->sortable(),
                
                Tables\Columns\ColorColumn::make('color_hex')
                    ->label('Cor Visual'),
                
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Estoque')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                
                Tables\Columns\TextColumn::make('price_adjustment')
                    ->label('Ajuste')
                    ->money('BRL'),
                
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Disp.')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nova Variação'),
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
