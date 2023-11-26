<?php

namespace App\Models;

use App\Enums\Status\BillStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => BillStatus::class,
    ];

    /**
     * Scope to get only unpaid bills
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', BillStatus::UNPAID);
    }
    /**
     * Scope to get only paid bills
     */
    public function scopePaid($query)
    {
        return $query->where('status', BillStatus::PAID)->orWhere('status', BillStatus::PARTIALLY_PAID);
    }

    /**
     * Relationship with Patient model
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship with Drug model
     */
    public function drugs(): BelongsToMany
    {
        return $this->belongsToMany(Drug::class);
    }

    /**
     * Relationship with Service model
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
}
