<?php

namespace App\Filament\Resources;

use App\Enums\Status\BillStatus;
use App\Filament\Resources\BillResource\Pages;
use App\Filament\Resources\BillResource\RelationManagers;
use App\Models\Bill;
use App\Models\Drug;
use App\Models\Service;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->label('Patient')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('extra_charges')
                    ->required()
                    ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => (new self())->updateTotalPrice($get, $set))
                    ->integer()
                    ->prefix('$')
                    ->default(0.00)
                    ->lazy(),
                Forms\Components\Select::make('drugs')
                    ->label('Drugs')
                    ->relationship('drugs', 'name')
                    ->searchable()
                    ->multiple()
                    ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => (new self())->updateTotalPrice($get, $set))
                    ->preload()
                    ->live(),
                Forms\Components\Select::make('services')
                    ->label('Services')
                    ->relationship('services', 'name')
                    ->searchable()
                    ->multiple()
                    ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => (new self())->updateTotalPrice($get, $set))
                    ->preload()
                    ->live(),
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->prefix('$')
                    ->disabled()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => $record->status->color())
                    ->formatStateUsing(fn ($record) => $record->status->name()),
                Tables\Columns\TextColumn::make('extra_charges')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('updateStatus')
                    ->label('Edit Status')
                    ->modalWidth('md')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options(BillStatus::getOptions())
                            ->placeholder('Select Status')
                            ->required(),
                    ])
                    ->icon('heroicon-m-pencil')
                    ->action(function (array $data, Bill $record): void {
                        if (!in_array($data['status'], array_keys(BillStatus::getOptions()))) {
                            return;
                        }
                        $record->update([
                            'status' => $data['status'],
                        ]);
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
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'view' => Pages\ViewBill::route('/{record}'),
        ];
    }

    private function updateTotalPrice(Forms\Get $get, Forms\Set $set)
    {
        $extraCharges = $get('extra_charges');
        $drugs = $get('drugs');
        $services = $get('services');

        $totalPrice = $extraCharges;

        foreach ($drugs as $drug) {
            $totalPrice += Drug::find($drug)?->price ?? 0;
        }

        foreach ($services as $service) {
            $totalPrice += Service::find($service)?->price ?? 0;
        }

        $set('total_price', $totalPrice);
    }
}
