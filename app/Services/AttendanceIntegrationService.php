<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

/**
 * Thin hook around laravel-attendance-hub.
 * In demo mode we never talk to real devices; we accept synced payloads.
 */
class AttendanceIntegrationService
{
    public function syncManualPunch(Employee $employee, ?\DateTimeInterface $checkIn = null, ?\DateTimeInterface $checkOut = null): AttendanceLog
    {
        $date = ($checkIn ?? now())->format('Y-m-d');

        return AttendanceLog::query()->updateOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => $date,
            ],
            [
                'business_id' => $employee->business_id,
                'check_in' => $checkIn ?? now()->setTime(9, 0),
                'check_out' => $checkOut,
                'source' => 'attendance_hub_hook',
                'status' => 'present',
            ]
        );
    }

    public function pullFromHub(int $businessId): int
    {
        // Real device sync would use AttendanceHub drivers; demo records a no-op.
        Log::info('attendance_hub.pull_skipped_demo', ['business_id' => $businessId]);

        return 0;
    }
}
