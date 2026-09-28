<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            'Casual Leave' => 10,
            'Medical Leave' => 14,
            'Earn Leave' => 12,
            'Matrimonial Leave' => 3,
            'Others' => 5,
        ];

        foreach ($leaveTypes as $name => $amountOfDays) {
            LeaveType::updateOrCreate(['name' => $name], ['amount_of_days' => $amountOfDays]);
        }
    }
}
