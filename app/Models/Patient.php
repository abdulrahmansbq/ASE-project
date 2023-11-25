<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * Relationship with Drug model
     */
    public function drugs()
    {
        return $this->belongsToMany(Drug::class, 'drug_patient', 'patient_id', 'drug_id');
    }
}
