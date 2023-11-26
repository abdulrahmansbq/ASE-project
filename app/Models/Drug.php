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
}
