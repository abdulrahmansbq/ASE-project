<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DrugsRelationManager extends RelationManager
{
    protected static string $relationship = 'drugs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('drugs')
                    ->label('Drugs')
                    ->options(
                        \App\Models\Drug::available()->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                //
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
