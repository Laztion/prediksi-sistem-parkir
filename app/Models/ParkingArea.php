<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingArea extends Model
{
    protected $fillable = ['name', 'location', 'total_slots', 'description', 'image_path'];

    public function parkingSlots()
    {
        return $this->hasMany(ParkingSlot::class);
    }

    public function occupancyLogs()
    {
        return $this->hasMany(OccupancyLog::class);
    }}
