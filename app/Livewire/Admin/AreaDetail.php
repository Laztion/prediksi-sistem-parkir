<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ParkingArea;
use App\Models\ParkingSlot;
use App\Models\ParkingSector;
use App\Models\Reservation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReservationsExport;

class AreaDetail extends Component
{
    public $areaId;
    public $activeTab = 'slots';

    public function exportToExcel()
    {
        return Excel::download(new ReservationsExport, 'data-reservasi-' . $this->areaId . '.xlsx');
    }

    public function confirmPayment($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        if ($reservation && $reservation->payment_status === 'pending') {
            $reservation->update(['payment_status' => 'paid']);
            session()->flash('message', 'Pembayaran untuk ' . $reservation->vehicle_plate_number . ' telah dikonfirmasi.');
        }
    }
    
    // For adding slots
    public $newSlotNumber;
    public $newSlotType = 'car';
    public $selectedSectorId;

    // For adding sectors
    public $newSectorName;
    public $newSectorCode;
    public $newSectorDescription;

    public function mount($id)
    {
        $this->areaId = $id;
    }

    public function addSector()
    {
        $this->validate([
            'newSectorName' => 'required|string|max:255',
            'newSectorCode' => 'nullable|string|max:50',
            'newSectorDescription' => 'nullable|string'
        ]);

        ParkingSector::create([
            'parking_area_id' => $this->areaId,
            'name' => $this->newSectorName,
            'code' => $this->newSectorCode,
            'description' => $this->newSectorDescription
        ]);

        $this->reset(['newSectorName', 'newSectorCode', 'newSectorDescription']);
        session()->flash('message', 'Sektor baru berhasil ditambahkan.');
    }

    public function addSlot()
    {
        $this->validate([
            'newSlotNumber' => 'required|string|unique:parking_slots,slot_number,NULL,id,parking_area_id,' . $this->areaId,
            'newSlotType' => 'required|in:car,motorcycle',
            'selectedSectorId' => 'nullable|exists:parking_sectors,id'
        ]);

        ParkingSlot::create([
            'parking_area_id' => $this->areaId,
            'parking_sector_id' => $this->selectedSectorId,
            'slot_number' => $this->newSlotNumber,
            'type' => $this->newSlotType,
            'status' => 'available'
        ]);

        $this->reset(['newSlotNumber', 'newSlotType', 'selectedSectorId']);
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
        $area = ParkingArea::with(['parkingSectors.parkingSlots', 'parkingSlots', 'occupancyLogs'])->findOrFail($this->areaId);
        
        $sectors = ParkingSector::where('parking_area_id', $this->areaId)->with('parkingSlots')->get();
        
        // Slots that don't have a sector
        $unassignedSlots = ParkingSlot::where('parking_area_id', $this->areaId)
            ->whereNull('parking_sector_id')
            ->get();
        $reservations = Reservation::with(['user', 'parkingSlot'])
            ->whereHas('parkingSlot', function($q) {
                $q->where('parking_area_id', $this->areaId);
            })
            ->latest()
            ->get();

        return view('livewire.admin.area-detail', [
            'area' => $area,
            'sectors' => $sectors,
            'unassignedSlots' => $unassignedSlots,
            'reservations' => $reservations
        ])->layout('layouts.app');
    }
}
