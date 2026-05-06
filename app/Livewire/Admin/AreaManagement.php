<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ParkingArea;
use Livewire\WithFileUploads;

class AreaManagement extends Component
{
    use WithFileUploads;

    public $name, $location, $total_slots, $description;
    public $latitude, $longitude, $google_maps_link, $map_image;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'total_slots' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'google_maps_link' => 'nullable|url',
        'map_image' => 'nullable|image|max:2048',
    ];

    public function saveArea()
    {
        $this->validate();

        $mapImagePath = $this->map_image ? $this->map_image->store('maps', 'public') : null;

        ParkingArea::create([
            'name' => $this->name,
            'location' => $this->location,
            'total_slots' => $this->total_slots,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'google_maps_link' => $this->google_maps_link,
            'map_image' => $mapImagePath,
        ]);

        session()->flash('message', 'Area parkir baru berhasil ditambahkan.');
        $this->reset(['name', 'location', 'total_slots', 'description', 'latitude', 'longitude', 'google_maps_link', 'map_image', 'showModal']);
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
