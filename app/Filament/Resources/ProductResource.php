<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Produtos';
    
    protected static ?string $modelLabel = 'Produto';
    
    protected static ?string $pluralModelLabel = 'Produtos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações Básicas')
                    ->description('Cadastre os dados principais do produto.')
                    ->schema([
                        Select::make('category_id')
                            ->label('Categoria')
                            ->options(Category::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->live(),
                        
                        TextInput::make('name')
                            ->label('Nome do Produto')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        
                        TextInput::make('slug')
                            ->label('URL Amigável (Slug)')
                            ->maxLength(255)
                            ->helperText('Gerado automaticamente.'),
                        
                        TextInput::make('base_price')
                            ->label('Preço de Venda')
                            ->required()
                            ->numeric()
                            ->prefix('R$')
                            ->step(0.01),

                        FileUpload::make('main_image')
                            ->label('Foto do Produto')
                            ->image()
                            ->directory('products')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Estoque e Tamanhos')
                    ->description('Defina a quantidade em estoque e os tamanhos disponíveis.')
                    ->schema([
                        TextInput::make('stock_quantity')
                            ->label('Quantidade Total em Estoque')
                            ->numeric()
                            ->default(fn ($record) => $record?->total_stock ?? 0)
                            ->minValue(0)
                            ->helperText('Se o produto tiver tamanhos, este estoque será dividido entre eles.')
                            ->required(),

                        CheckboxList::make('sizes')
                            ->label('Tamanhos Disponíveis')
                            ->options([
                                'PP' => 'PP',
                                'P' => 'P',
                                'M' => 'M',
                                'G' => 'G',
                                'GG' => 'GG',
                                'XG' => 'XG',
                                'U' => 'Único',
                            ])
                            ->columns(4)
                            ->gridDirection('row')
                            ->default(fn ($record) => $record?->variants()->pluck('size')->toArray() ?? [])
                            ->visible(fn (Forms\Get $get) => Category::find($get('category_id'))?->has_sizes ?? true)
                            ->helperText('Selecione os tamanhos que este produto possui.'),
                    ]),
                
                Section::make('Descrição e Detalhes')
                    ->schema([
                        RichEditor::make('description')
                            ->label('Descrição')
                            ->required()
                            ->columnSpanFull(),
                        
                        Toggle::make('is_active')
                            ->label('Produto Ativo')
                            ->default(true),
                        
                        Toggle::make('is_featured')
                            ->label('Destaque na Home')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['category']))
            ->columns([
                ImageColumn::make('main_image')
                    ->label('Foto')
                    ->circular(),
                
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                
                TextColumn::make('base_price')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),
                
                TextColumn::make('total_stock')
                    ->label('Estoque Total')
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Categoria'),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
