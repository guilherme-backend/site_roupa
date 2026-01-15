<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationLabel = 'Pedidos';
    
    protected static ?string $modelLabel = 'Pedido';
    
    protected static ?string $pluralModelLabel = 'Pedidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Pedido')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Número do Pedido')
                            ->disabled(),
                        Forms\Components\Select::make('user_id')
                            ->label('Cliente')
                            ->relationship('user', 'name')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending_payment' => 'Aguardando Pagamento',
                                'paid' => 'Pago',
                                'processing' => 'Em Processamento',
                                'shipped' => 'Enviado',
                                'delivered' => 'Entregue',
                                'cancelled' => 'Cancelado',
                                'refunded' => 'Reembolsado',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('tracking_code')
                            ->label('Código de Rastreamento')
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('Informações de Entrega')
                    ->schema([
                        Forms\Components\TextInput::make('shipping_name')
                            ->label('Nome')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_email')
                            ->label('Email')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_phone')
                            ->label('Telefone')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_zipcode')
                            ->label('CEP')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_address')
                            ->label('Endereço')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_number')
                            ->label('Número')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_complement')
                            ->label('Complemento')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_neighborhood')
                            ->label('Bairro')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_city')
                            ->label('Cidade')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_state')
                            ->label('Estado')
                            ->disabled(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Valores')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->disabled()
                            ->prefix('R$'),
                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Frete')
                            ->disabled()
                            ->prefix('R$'),
                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->disabled()
                            ->prefix('R$'),
                        Forms\Components\TextInput::make('shipping_method')
                            ->label('Método de Envio')
                            ->disabled(),
                        Forms\Components\TextInput::make('payment_method')
                            ->label('Método de Pagamento')
                            ->disabled(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Observações')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Observações')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Pedido')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending_payment',
                        'success' => 'paid',
                        'primary' => 'processing',
                        'info' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                        'secondary' => 'refunded',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending_payment' => 'Aguardando Pagamento',
                        'paid' => 'Pago',
                        'processing' => 'Em Processamento',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregue',
                        'cancelled' => 'Cancelado',
                        'refunded' => 'Reembolsado',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Pagamento')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pix' => 'PIX',
                        'credit_card' => 'Cartão',
                        'boleto' => 'Boleto',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending_payment' => 'Aguardando Pagamento',
                        'paid' => 'Pago',
                        'processing' => 'Em Processamento',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregue',
                        'cancelled' => 'Cancelado',
                        'refunded' => 'Reembolsado',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Método de Pagamento')
                    ->options([
                        'pix' => 'PIX',
                        'credit_card' => 'Cartão',
                        'boleto' => 'Boleto',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
