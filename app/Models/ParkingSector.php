<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSector extends Model
{
    protected $fillable = ['parking_area_id', 'name', 'code', 'description'];

    public function parkingArea(): BelongsTo
    {
        return $this->belongsTo(ParkingArea::class);
    }

    public function parkingSlots(): HasMany
    {
        return $this->hasMany(ParkingSlot::class);
    }
}
