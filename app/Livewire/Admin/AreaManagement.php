<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ParkingArea;
use Livewire\WithFileUploads;

class AreaManagement extends Component
{
    use WithFileUploads;

    public $name, $location, $total_slots, $description;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'total_slots' => 'required|integer|min:1',
        'description' => 'nullable|string',
    ];

    public function saveArea()
    {
        $this->validate();

        ParkingArea::create([
            'name' => $this->name,
            'location' => $this->location,
            'total_slots' => $this->total_slots,
            'description' => $this->description,
        ]);

        session()->flash('message', 'Area parkir baru berhasil ditambahkan.');
        $this->reset(['name', 'location', 'total_slots', 'description', 'showModal']);
    }

    public function deleteArea($id)
    {
        ParkingArea::find($id)->delete();
        session()->flash('message', 'Area parkir berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.area-management', [
            'areas' => ParkingArea::latest()->get()
        ])->layout('layouts.app');
    }
}
