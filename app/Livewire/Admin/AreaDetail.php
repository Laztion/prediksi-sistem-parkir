<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ParkingArea;
use App\Models\ParkingSlot;
use App\Models\Reservation;

class AreaDetail extends Component
{
    public $areaId;
    public $activeTab = 'slots';
    
    // For adding slots
    public $newSlotNumber;
    public $newSlotType = 'car';

    public function mount($id)
    {
        $this->areaId = $id;
    }

    public function addSlot()
    {
        $this->validate([
            'newSlotNumber' => 'required|string|unique:parking_slots,slot_number,NULL,id,parking_area_id,' . $this->areaId,
            'newSlotType' => 'required|in:car,motorcycle'
        ]);

        ParkingSlot::create([
            'parking_area_id' => $this->areaId,
            'slot_number' => $this->newSlotNumber,
            'type' => $this->newSlotType,
            'status' => 'available'
        ]);

        $this->reset(['newSlotNumber', 'newSlotType']);
        session()->flash('message', 'Slot baru berhasil ditambahkan.');
    }

    public function toggleSlotStatus($slotId)
    {
        $slot = ParkingSlot::find($slotId);
        $newStatus = $slot->status === 'available' ? 'occupied' : 'available';
        $slot->update(['status' => $newStatus]);
    }

    public function render()
    {
        $area = ParkingArea::with(['parkingSlots', 'occupancyLogs'])->findOrFail($this->areaId);
        
        $reservations = Reservation::with(['user', 'parkingSlot'])
            ->whereHas('parkingSlot', function($q) {
                $q->where('parking_area_id', $this->areaId);
            })
            ->latest()
            ->get();

        return view('livewire.admin.area-detail', [
            'area' => $area,
            'reservations' => $reservations
        ])->layout('layouts.app');
    }
}
