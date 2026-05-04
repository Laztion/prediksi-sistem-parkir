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
    public $stats = [];
    public $myReservations = [];
    public $recentLogs = [];

    public function mount()
    {
        $user = Auth::user();
        
        // 1. User Features: My Reservations
        $this->myReservations = Reservation::with('parkingSlot.parkingArea')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 2. Admin Features: Global Stats
        if ($user->hasRole('admin')) {
            $this->stats = [
                'total_users' => User::count(),
                'total_areas' => ParkingArea::count(),
                'total_slots' => ParkingSlot::count(),
                'active_reservations' => Reservation::where('status', 'active')->count(),
            ];
        }
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
