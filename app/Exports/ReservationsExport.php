<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Reservation::with(['user', 'parkingSlot.parkingArea'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Area',
            'Slot',
            'Plat Nomor',
            'Kendaraan',
            'Waktu Masuk',
            'Waktu Keluar',
            'Status',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Total',
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->id,
            $reservation->user->name,
            $reservation->parkingSlot->parkingArea->name,
            $reservation->parkingSlot->slot_number,
            $reservation->vehicle_plate_number,
            $reservation->vehicle_model,
            $reservation->start_time,
            $reservation->end_time,
            $reservation->status,
            $reservation->payment_method,
            $reservation->payment_status,
            $reservation->amount,
        ];
    }
}
