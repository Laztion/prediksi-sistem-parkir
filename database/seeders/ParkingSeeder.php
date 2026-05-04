<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ParkingArea;
use App\Models\ParkingSlot;
use App\Models\OccupancyLog;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Setup Roles & Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@smartpark.id'],
            [
                'name' => 'Administrator SmartPark',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->assignRole($adminRole);

        // 2. Indonesian Parking Areas
        $areas = [
            [
                'name' => 'Grand Indonesia West Mall',
                'location' => 'Menteng, Jakarta Pusat',
                'total_slots' => 50,
                'description' => 'Area parkir strategis di jantung kota Jakarta.',
            ],
            [
                'name' => 'Senayan City',
                'location' => 'Gelora, Jakarta Pusat',
                'total_slots' => 40,
                'description' => 'Parkir premium dengan akses mudah ke area perkantoran.',
            ],
            [
                'name' => 'Paris Van Java (PVJ)',
                'location' => 'Sukajadi, Bandung',
                'total_slots' => 30,
                'description' => 'Lantai P1 - P4 dengan pemantauan CCTV 24 jam.',
            ],
            [
                'name' => 'Tunjungan Plaza 6',
                'location' => 'Tegalsari, Surabaya',
                'total_slots' => 45,
                'description' => 'Fasilitas parkir modern dengan sensor ketersediaan pintar.',
            ]
        ];

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $times = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'];

        foreach ($areas as $areaData) {
            $area = ParkingArea::firstOrCreate(
                ['name' => $areaData['name']],
                $areaData
            );

            // If area already has slots, skip creation to avoid duplicates
            if ($area->parkingSlots()->count() == 0) {
                // Create Slots
                for ($i = 1; $i <= $areaData['total_slots']; $i++) {
                    $status = rand(1, 10) > 8 ? 'occupied' : 'available'; // 20% occupied randomly
                    ParkingSlot::create([
                        'parking_area_id' => $area->id,
                        'slot_number' => 'B' . str_pad($i, 2, '0', STR_PAD_LEFT),
                        'status' => $status,
                        'type' => $i % 5 == 0 ? 'motorcycle' : 'car', // Mix of types
                    ]);
                }
            }

            // If area already has logs, skip creation to avoid duplicates
            if ($area->occupancyLogs()->count() == 0) {
                // Create Occupancy Logs for Naive Bayes Training
                foreach ($days as $day) {
                    foreach ($times as $time) {
                        $status = 'available';
                        
                        // Business Logic Simulation:
                        // Weekend afternoons are almost always full in Indo Malls
                        if (in_array($day, ['Saturday', 'Sunday']) && in_array($time, ['14:00', '16:00', '18:00', '20:00'])) {
                            $status = 'full';
                        } 
                        // Lunch hours on weekdays are busy
                        elseif (!in_array($day, ['Saturday', 'Sunday']) && $time == '12:00') {
                            $status = rand(0, 1) ? 'full' : 'available';
                        }
                        // Night life on Friday/Saturday
                        elseif (in_array($day, ['Friday', 'Saturday']) && $time == '22:00') {
                            $status = 'full';
                        }

                        OccupancyLog::create([
                            'parking_area_id' => $area->id,
                            'day_of_week' => $day,
                            'time_slot' => $time,
                            'occupancy_status' => $status,
                        ]);
                    }
                }
            }
        }
    }
}
