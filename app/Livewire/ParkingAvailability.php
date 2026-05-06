<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ParkingArea;
use App\Models\ParkingSlot;
use App\Models\Reservation;
use App\Services\NaiveBayesService;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReservationsExport;

class ParkingAvailability extends Component
{
    public $selectedAreaId;
    public $prediction = null;
    public $probability = 0;
    public $dailyTimeline = [];
    
    // Future Prediction Inputs
    public $predictionDay;
    public $predictionTime;
    public $isFuture = false;

    // Reservation info
    public $showReservationModal = false;
    public $slotToReserve = null;
    public $vehiclePlateNumber;
    public $vehicleModel;
    public $selectedPaymentMethod = 'e-wallet';
    public $reservationStep = 1; // 1: Vehicle Info, 2: Payment Process

    public function mount()
    {
        $this->selectedAreaId = ParkingArea::first()?->id;
        $this->predictionDay = now()->format('l');
        $this->predictionTime = now()->format('H:00');
        $this->updatePrediction();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selectedAreaId', 'predictionDay', 'predictionTime'])) {
            $this->updatePrediction();
        }
    }

    public function updatePrediction()
    {
        if (!$this->selectedAreaId) return;

        $service = new NaiveBayesService();
        
        // Check if it's future
        $now = now();
        $selectedTime = \Carbon\Carbon::parse("next " . $this->predictionDay)->setTimeFromTimeString($this->predictionTime);
        if ($selectedTime->isPast()) {
             // If today is Monday and we selected Monday, Carbon 'next Monday' might be next week.
             // We want the closest one.
             $selectedTime = \Carbon\Carbon::parse($this->predictionDay)->setTimeFromTimeString($this->predictionTime);
        }
        
        $this->isFuture = $selectedTime->isFuture();

        $this->prediction = $service->predict($this->selectedAreaId, $this->predictionDay, $this->predictionTime);
        $this->probability = $service->getAvailabilityPercentage($this->selectedAreaId, $this->predictionDay, $this->predictionTime);
        
        // Calculate Timeline
        $this->dailyTimeline = [];
        for ($h = 0; $h < 24; $h++) {
            $time = sprintf('%02d:00', $h);
            $this->dailyTimeline[$time] = $service->getAvailabilityPercentage($this->selectedAreaId, $this->predictionDay, $time);
        }
    }

    public function openReserveModal($slotId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->slotToReserve = ParkingSlot::find($slotId);
        $this->reservationStep = 1;
        $this->showReservationModal = true;
    }

    public function reserve()
    {
        $this->validate([
            'vehiclePlateNumber' => 'required|string|max:15',
            'vehicleModel' => 'required|string|max:255',
            'selectedPaymentMethod' => 'required|in:e-wallet,credit_card,cash,qris'
        ]);

        if ($this->selectedPaymentMethod === 'cash') {
            $this->finalizeReservation();
        } else {
            $this->reservationStep = 2;
        }
    }

    public function finalizeReservation()
    {
        if (!$this->slotToReserve) return;

        $this->slotToReserve->update(['status' => 'reserved']);
        
        Reservation::create([
            'user_id' => Auth::id(),
            'parking_slot_id' => $this->slotToReserve->id,
            'vehicle_plate_number' => $this->vehiclePlateNumber,
            'vehicle_model' => $this->vehicleModel,
            'start_time' => now(),
            'end_time' => now()->addHour(),
            'status' => 'active',
            'payment_method' => $this->selectedPaymentMethod,
            'payment_status' => $this->selectedPaymentMethod === 'cash' ? 'pending' : 'paid',
            'amount' => 5000,
        ]);

        session()->flash('message', 'Slot ' . $this->slotToReserve->slot_number . ' berhasil direservasi!');
        $this->reset(['showReservationModal', 'slotToReserve', 'vehiclePlateNumber', 'vehicleModel', 'reservationStep']);
    }

    public function exportToExcel()
    {
        return Excel::download(new ReservationsExport, 'data-reservasi.xlsx');
    }

    public function render()
    {
        $area = $this->selectedAreaId ? ParkingArea::with(['parkingSectors.parkingSlots', 'parkingSlots'])->find($this->selectedAreaId) : null;
        
        return view('livewire.parking-availability', [
            'areas' => ParkingArea::all(),
            'area' => $area,
            'sectors' => $area ? $area->parkingSectors : [],
            'unassignedSlots' => $area ? $area->parkingSlots()->whereNull('parking_sector_id')->get() : [],
        ]);
    }
}
