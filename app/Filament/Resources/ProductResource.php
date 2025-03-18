<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\OrderItem;
use Filament\Tables\Actions\DeleteAction;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('price')->numeric()->required(),
                Forms\Components\FileUpload::make('image')
                    ->disk('public') 
                    ->directory('products')
                    ->image()
                    ->nullable()
                    ->label('Product Image')
                    ->getUploadedFileNameForStorageUsing(fn ($file) => 
                        Carbon::now()->format('YmdHisv') . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension()
                    ),
                    Forms\Components\Select::make('categories')
                    ->relationship('categories', 'name')
                    ->label('Category'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('image')
                ->disk('public') // Load from storage
                ->default(fn ($record) => $record->image 
                    ? asset("storage/{$record->image}") // If image exists, load from storage
                    : 'https://picsum.photos/400/300?random=' . rand(1, 1000) // Else, load placeholder
                )
                ->label('Product Image'),
                Tables\Columns\TextColumn::make('price')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('categories.name')
                ->searchable()
                ->label('Categories'),
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->before(function (DeleteAction $action, $record) {
                    if ($record->items()->exists()) {
                        // Show notification
                        Notification::make()
                            ->title('Cannot Delete Product')
                            ->body('This product has associated order items and cannot be deleted.')
                            ->danger()
                            ->send();

                        $action->halt();
                    }
                }),

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
            //
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
