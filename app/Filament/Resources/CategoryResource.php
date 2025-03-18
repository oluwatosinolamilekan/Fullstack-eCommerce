<?php

namespace App\Filament\Resources;

use Exception;
use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Select::make('parent_id')
                    ->searchable()
                    ->relationship('parent', 'name')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->query(Category::whereNotNull('parent_id'))
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('parent.name')->label('Parent Category'),
                TextColumn::make('products_count')
                ->label('Product Count')
                ->getStateUsing(function ($record) {
                    return $record->products()->count();
                }),
            ])
            ->filters([
            ])
            ->searchPlaceholder('Search categories...')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make('delete')
                ->label('Delete')
                ->before(function (DeleteAction $action, Category $category) {
                    if ($category->products()->exists()) {
                        Notification::make()
                            ->title('Cannot Delete Category')
                            ->body('This category has associated products and cannot be deleted.')
                            ->danger()
                            ->send();

                        $action->halt(); // Prevent deletion
                    }
                })
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
