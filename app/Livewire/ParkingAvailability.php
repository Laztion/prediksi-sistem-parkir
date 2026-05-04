<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ParkingArea;
use App\Models\ParkingSlot;
use App\Models\Reservation;
use App\Services\NaiveBayesService;
use Illuminate\Support\Facades\Auth;

class ParkingAvailability extends Component
{
    public $selectedAreaId;
    public $prediction = null;
    public $probability = 0;

    public function mount()
    {
        $this->selectedAreaId = ParkingArea::first()?->id;
        $this->updatePrediction();
    }

    public function updatedSelectedAreaId()
    {
        $this->updatePrediction();
    }

    public function updatePrediction()
    {
        if (!$this->selectedAreaId) return;

        $service = new NaiveBayesService();
        $day = now()->format('l');
        $time = now()->format('H:00');

        $this->prediction = $service->predict($this->selectedAreaId, $day, $time);
        $this->probability = $service->getAvailabilityPercentage($this->selectedAreaId, $day, $time);
    }

    public function reserve($slotId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $slot = ParkingSlot::find($slotId);
        if ($slot->status === 'available') {
            $slot->update(['status' => 'reserved']);
            
            Reservation::create([
                'user_id' => Auth::id(),
                'parking_slot_id' => $slotId,
                'start_time' => now(),
                'end_time' => now()->addHour(),
                'status' => 'active',
            ]);

            session()->flash('message', 'Slot ' . $slot->slot_number . ' berhasil direservasi!');
        }
    }

    public function render()
    {
        return view('livewire.parking-availability', [
            'areas' => ParkingArea::all(),
            'slots' => $this->selectedAreaId ? ParkingSlot::where('parking_area_id', $this->selectedAreaId)->get() : [],
        ]);
    }
}
