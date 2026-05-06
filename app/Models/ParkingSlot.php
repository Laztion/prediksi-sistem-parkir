<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    protected $fillable = ['parking_area_id', 'parking_sector_id', 'slot_number', 'status', 'type'];

    public function parkingArea()
    {
        return $this->belongsTo(ParkingArea::class);
    }

    public function parkingSector()
    {
        return $this->belongsTo(ParkingSector::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }}
