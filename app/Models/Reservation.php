<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 
        'parking_slot_id', 
        'vehicle_plate_number',
        'vehicle_model',
        'start_time', 
        'end_time', 
        'status',
        'payment_method',
        'payment_status',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }}
