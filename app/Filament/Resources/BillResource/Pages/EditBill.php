<?php

namespace App\Filament\Resources\BillResource\Pages;

use App\Filament\Resources\BillResource;
use App\Models\Drug;
use App\Models\Service;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBill extends EditRecord
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $totalDrugsPrice = Drug::findMany($this->data['drugs'])->sum('price');
        $totalServicesPrice = Service::findMany($this->data['services'])->sum('price');
        $totalPrice = $totalDrugsPrice + $totalServicesPrice + $this->data['extra_charges'];
        $data['total_price'] = $totalPrice;
        return $data;
    }
}
