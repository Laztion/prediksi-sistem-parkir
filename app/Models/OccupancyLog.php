<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OccupancyLog extends Model
{
    protected $fillable = ['parking_area_id', 'day_of_week', 'time_slot', 'occupancy_status'];

    public function parkingArea()
    {
        return $this->belongsTo(ParkingArea::class);
    }}
