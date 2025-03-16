<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Subcategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubcategoryResource\Pages;
use App\Filament\Resources\SubcategoryResource\RelationManagers;

class SubcategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Parent Categories';
    protected static ?string $pluralLabel = 'Parent Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('name')
                // ->required()
                // ->label('Subcategory Name'),
                TextInput::make('name')
                    ->required()
                    ->label('Subcategory Name')
                    ->unique(ignoreRecord: true) // Ensure subcategory name is unique
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, $fail) use ($get) {
                                $parentId = $get('parent_id');
                                if ($parentId) {
                                    $parentCategory = Category::find($parentId);
                                    if ($parentCategory && $parentCategory->name === $value) {
                                        $fail('The subcategory name cannot be the same as the parent category name.');
                                    }
                                }
                            };
                        },
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Category::whereNull('parent_id'))
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Parent Category'),

            ])
            ->filters([
                SelectFilter::make('parent_id')
                ->label('Filter by Parent Category')
                ->options(Category::whereNull('parent_id')->pluck('name', 'id')),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubcategories::route('/'),
            'create' => Pages\CreateSubcategory::route('/create'),
            'edit' => Pages\EditSubcategory::route('/{record}/edit'),
        ];
    }
}
