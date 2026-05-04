<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationManagement extends Component
{
    public function verifyCheckIn($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->status === 'pending') {
            $reservation->update(['status' => 'active']);
            $reservation->parkingSlot->update(['status' => 'occupied']);
            session()->flash('message', 'Check-in berhasil diverifikasi.');
        }
    }

    public function completeReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->status === 'active') {
            $reservation->update(['status' => 'completed']);
            $reservation->parkingSlot->update(['status' => 'available']);
            session()->flash('message', 'Reservasi telah selesai.');
        }
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'cancelled']);
        $reservation->parkingSlot->update(['status' => 'available']);
        session()->flash('message', 'Reservasi dibatalkan.');
    }

    public function render()
    {
        $reservations = Reservation::with(['user', 'parkingSlot.parkingArea'])
            ->latest()
            ->paginate(10);

        return view('livewire.admin.reservation-management', [
            'reservations' => $reservations
        ])->layout('layouts.app');
    }
}
