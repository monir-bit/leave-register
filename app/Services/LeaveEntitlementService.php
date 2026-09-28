<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveType;

class LeaveEntitlementService
{
    /**
     * Number of months the employee was actively employed during the given year,
     * based on their joining date and (if applicable) their dismissal date.
     */
    public function monthsWorkedInYear(Employee $employee, int $year): int
    {
        $joinDate = $employee->date_of_joining;
        $dismissedDate = $employee->dismissed_at;

        if ($joinDate->year > $year) {
            return 0;
        }

        if ($dismissedDate && $dismissedDate->year < $year) {
            return 0;
        }

        $startMonth = $joinDate->year === $year ? $joinDate->month : 1;
        $endMonth = ($dismissedDate && $dismissedDate->year === $year) ? $dismissedDate->month : 12;

        return max(0, $endMonth - $startMonth + 1);
    }

    /**
     * The number of days of this leave type the employee has earned for the given year,
     * pro-rated by the number of months actually worked that year.
     */
    public function calculateAvailableDays(Employee $employee, LeaveType $leaveType, int $year): int
    {
        if ($leaveType->amount_of_days <= 0) {
            return 0;
        }

        $months = $this->monthsWorkedInYear($employee, $year);

        return (int) round((12 / $leaveType->amount_of_days) * $months);
    }
}
