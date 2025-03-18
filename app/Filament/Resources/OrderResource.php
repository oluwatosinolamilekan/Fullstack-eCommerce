<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\ProductRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->relationship('user', 'name') 
                ->required()
                ->label('Customer'),

            Forms\Components\TextInput::make('customer_email')
                ->label('Email Address')
                ->dehydrated(false) 
                ->afterStateHydrated(fn ($state, callable $set, $record) => 
                    $set('customer_email', $record?->user?->email ?? '')
                )
                ,
                Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'cancelled' => 'Cancelled',
                ])
                ->required()
                ->label('Status')
                ->default(fn ($record) => $record?->status), // Ensure status is pre-selected
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name') // ✅ Fetch customer name from User model
                ->label('Customer Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('user.email') // ✅ Fetch email from User model
                ->label('Email Address')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('total_quantity')
                ->label('Total Quantity')
                ->getStateUsing(fn (Order $order) => $order->items->sum('quantity')),
                BadgeColumn::make('status')
                    ->colors([
                        OrderStatus::PENDING->value => 'gray',
                        OrderStatus::PROCESSING->value => 'blue',
                        OrderStatus::SHIPPED->value => 'green',
                        OrderStatus::CANCELLED->value => 'red',
                    ])
                    
                    ->label(fn (Order $record) => match ($record->status) {
                        OrderStatus::PENDING->value => 'Pending',
                        OrderStatus::PROCESSING->value => 'Processing',
                        OrderStatus::SHIPPED->value => 'Shipped',
                        OrderStatus::CANCELLED->value => 'Cancelled',
                    })
                    ->label('Status'),
                            
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
               
                Tables\Actions\DeleteAction::make('delete')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->action(function (Order $order) {
                    // Get the count of items before deletion for the modal description
                    $itemCount = $order->items->sum('quantity');
                    
                    // Delete all related items first (cascade delete if not using foreign keys)
                    $order->items()->delete(); // Delete related order items

                    // Now delete the order itself
                    $order->delete();
                    
                    // Send a success notification after deletion
                    Notification::make()
                        ->title('Order Deleted')
                        ->body("You have successfully deleted the order and its {$itemCount} item(s).")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()  // This will show the confirmation modal
                ->modalHeading('Delete Order')  // Customize the modal heading
                ->modalDescription(function (Order $order) {
                    return "Are you sure you want to delete this order with " . $order->items->sum('quantity') . " item(s)? This action cannot be undone.";
                })
                ->modalSubmitActionLabel('Yes, delete it')  // Label for the confirmation button
                ->color('danger'), // Color of the action button
                
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
            ProductRelationManager::class, // ✅ Show product list inside orders
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' =>   Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
