<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Enums\Status\BillStatus;
use App\Filament\Resources\PatientResource;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;



    public function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->columns(5)
            ->schema([
                Section::make('Personal Information')
                    ->description('Basic information about the patient.')
                    ->columnSpan(4)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Full Name')
                            ->columns(1),
                        TextEntry::make('email')
                            ->label('Email')
                            ->columns(1),
                        TextEntry::make('national_id')
                            ->label('National ID')
                            ->columns(1),
                        TextEntry::make('dob')
                            ->label('Date of Birth')
                            ->columns(1),
                    ]),
                Section::make('Dues Details')
                    ->description('unpaid and paid bills.')
                    ->extraAttributes(['class' => 'h-full'])
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        TextEntry::make('name')
                            ->hiddenLabel()
                            ->alignCenter()
                            ->extraAttributes(['class' => 'mt-3'])
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::ExtraBold)
                            ->color('success')
                            ->formatStateUsing(function (string $state) {
                                return '$' . number_format($this->getRecord()->bills()->paid()->sum('total_price'), 2);
                            })
                            ->columns(1),
                        TextEntry::make('name')
                            ->hiddenLabel()
                            ->alignCenter()
                            ->extraAttributes(['class' => 'mt-3'])
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::ExtraBold)
                            ->color('danger')
                            ->formatStateUsing(function (string $state) {
                                return '$' . number_format($this->getRecord()->bills()->unpaid()->sum('total_price'), 2);
                            })
                            ->columns(1),
                    ]),

                RepeatableEntry::make('bills')
                    ->label('Bills')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID')
                            ->columns(1),
                        TextEntry::make('total_price')
                            ->label('Total Price')
                            ->columns(1),
                        TextEntry::make('extra_charges')
                            ->label('Extra Charges')
                            ->columns(1),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($record) => $record->status->color())
                            ->formatStateUsing(fn ($record) => $record->status->name())
                            ->columns(1),
                        TextEntry::make('created_at')
                            ->label('Issued At')
                            ->columns(1),
                        RepeatableEntry::make('drugs')
                            ->columnSpanFull()
                            ->grid()
                            ->columns()
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Name')
                                    ->columns(1),
                                TextEntry::make('price')
                                    ->label('Price')
                                    ->columns(1),
                            ]),
                        RepeatableEntry::make('Services')
                            ->columnSpanFull()
                            ->grid(2)
                            ->columns(2)
                            ->contained(true)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Name')
                                    ->columns(1),
                                TextEntry::make('price')
                                    ->label('Price')
                                    ->columns(1),
                            ]),
                    ]),
            ]);
    }
}
