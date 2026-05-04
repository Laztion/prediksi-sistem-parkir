<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ParkingArea;
use App\Models\ParkingSlot;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $areas = [];
    public $selectedAreaId = null;
    public $availableSlots = [];
    public $showSlots = false;
    public $myReservations = [];
    public $stats = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = Auth::user();
        
        // 1. My Reservations
        $this->myReservations = Reservation::with('parkingSlot.parkingArea')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 2. Parking Areas with Availability
        $this->areas = ParkingArea::withCount(['parkingSlots as available_count' => function ($query) {
            $query->where('status', 'available');
        }])->get();

        // 3. Admin Features: Global Stats
        if ($user->hasRole('admin')) {
            $this->stats = [
                'total_users' => User::count(),
                'total_areas' => ParkingArea::count(),
                'total_slots' => ParkingSlot::count(),
                'active_reservations' => Reservation::where('status', 'active')->count(),
            ];
        }
    }

    public function selectArea($id)
    {
        $this->selectedAreaId = $id;
        $this->availableSlots = ParkingSlot::where('parking_area_id', $id)->get();
        $this->showSlots = true;
    }

    public function reserveSlot($slotId)
    {
        $slot = ParkingSlot::findOrFail($slotId);

        if ($slot->status !== 'available') {
            session()->flash('error', 'Slot ini sudah tidak tersedia.');
            return;
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'parking_slot_id' => $slotId,
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => 'pending',
        ]);

        $slot->update(['status' => 'reserved']);

        session()->flash('message', 'Reservasi berhasil dibuat!');
        $this->showSlots = false;
        $this->loadData();
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::find($id);
        if ($reservation && $reservation->user_id == Auth::id()) {
            $reservation->parkingSlot->update(['status' => 'available']);
            $reservation->update(['status' => 'cancelled']);
            session()->flash('message', 'Reservasi berhasil dibatalkan.');
            $this->mount(); // Refresh data
        }
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}
