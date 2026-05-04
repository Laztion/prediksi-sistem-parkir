<?php

namespace App\Services;

use App\Models\OccupancyLog;
use Illuminate\Support\Facades\DB;

class NaiveBayesService
{
    /**
     * Prediksi ketersediaan parkir berdasarkan data histori.
     * 
     * @param int $parkingAreaId
     * @param string $dayOfWeek (Monday, Tuesday, etc.)
     * @param string $timeSlot (HH:00 format or range)
     * @return string (available|full)
     */
    public function predict($parkingAreaId, $dayOfWeek, $timeSlot)
    {
        $logs = OccupancyLog::where('parking_area_id', $parkingAreaId)->get();
        
        if ($logs->isEmpty()) {
            return 'available';
        }

        $totalLogs = $logs->count();
        $statuses = ['available', 'full'];
        $probabilities = [];

        foreach ($statuses as $status) {
            $statusCount = $logs->where('occupancy_status', $status)->count();
            
            // Prior Probability: P(Status)
            $prior = ($statusCount + 1) / ($totalLogs + 2); // Laplace smoothing

            // Likelihood: P(Day | Status)
            $dayMatchCount = $logs->where('occupancy_status', $status)
                                 ->where('day_of_week', $dayOfWeek)
                                 ->count();
            $pDay = ($dayMatchCount + 1) / ($statusCount + 7); // 7 days in a week

            // Likelihood: P(Time | Status)
            $timeMatchCount = $logs->where('occupancy_status', $status)
                                  ->where('time_slot', $timeSlot)
                                  ->count();
            $pTime = ($timeMatchCount + 1) / ($statusCount + 24); // Assuming 24 hour slots

            $probabilities[$status] = $prior * $pDay * $pTime;
        }

        // Return status with highest probability
        return $probabilities['available'] >= $probabilities['full'] ? 'available' : 'full';
    }

    /**
     * Hitung persentase probabilitas ketersediaan.
     */
    public function getAvailabilityPercentage($parkingAreaId, $dayOfWeek, $timeSlot)
    {
        $logs = OccupancyLog::where('parking_area_id', $parkingAreaId)->get();
        
        if ($logs->isEmpty()) return 100;

        $totalLogs = $logs->count();
        
        $status = 'available';
        $statusCount = $logs->where('occupancy_status', $status)->count();
        $prior = ($statusCount + 1) / ($totalLogs + 2);

        $dayMatchCount = $logs->where('occupancy_status', $status)->where('day_of_week', $dayOfWeek)->count();
        $pDay = ($dayMatchCount + 1) / ($statusCount + 7);

        $timeMatchCount = $logs->where('occupancy_status', $status)->where('time_slot', $timeSlot)->count();
        $pTime = ($timeMatchCount + 1) / ($statusCount + 24);

        $probAvailable = $prior * $pDay * $pTime;

        // Repeat for 'full'
        $statusFull = 'full';
        $statusCountFull = $logs->where('occupancy_status', $statusFull)->count();
        $priorFull = ($statusCountFull + 1) / ($totalLogs + 2);
        $dayMatchCountFull = $logs->where('occupancy_status', $statusFull)->where('day_of_week', $dayOfWeek)->count();
        $pDayFull = ($dayMatchCountFull + 1) / ($statusCountFull + 7);
        $timeMatchCountFull = $logs->where('occupancy_status', $statusFull)->where('time_slot', $timeSlot)->count();
        $pTimeFull = ($timeMatchCountFull + 1) / ($statusCountFull + 24);

        $probFull = $priorFull * $pDayFull * $pTimeFull;

        $totalProb = $probAvailable + $probFull;
        
        return round(($probAvailable / $totalProb) * 100, 2);
    }
}
