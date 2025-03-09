<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\RelationManagers\RelationManager;

class ProductRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('product.image') 
                ->label('Product Image')
                ->circular(),
                TextColumn::make('product.name')->label('Product Name')->sortable(),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                TextColumn::make('price')->label('Price')->money('USD')->sortable(),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
