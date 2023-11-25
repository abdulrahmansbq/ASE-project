<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;

    /**
     * Scope to get only available drugs
     */
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Relationship with Patient model
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patient_drug', 'drug_id', 'patient_id');
    }
}
