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
                Section::make('Informações do Produto')
                    ->description('Cadastre os dados principais do produto e sua imagem de destaque.')
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
                            ->helperText('Gerado automaticamente a partir do nome.'),
                        
                        TextInput::make('base_price')
                            ->label('Preço Base')
                            ->required()
                            ->numeric()
                            ->prefix('R$')
                            ->step(0.01),

                        FileUpload::make('main_image')
                            ->label('Foto Principal')
                            ->image()
                            ->directory('products')
                            ->imageEditor()
                            ->columnSpanFull()
                            ->helperText('Esta será a imagem exibida na vitrine da loja.'),

                        RichEditor::make('description')
                            ->label('Descrição Detalhada')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Section::make('Configurações e Visibilidade')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Produto Ativo')
                            ->helperText('Se desmarcado, o produto não aparecerá na loja.')
                            ->default(true),
                        
                        Toggle::make('is_featured')
                            ->label('Destaque na Home')
                            ->helperText('Exibir este produto na seção de destaques.')
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
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                
                TextColumn::make('base_price')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable()
                    ->color('success'),
                
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                
                TextColumn::make('created_at')
                    ->label('Cadastro')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
