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
            'Casual Leave' => ['amount_of_days' => 10, 'position' => 1],
            'Medical Leave' => ['amount_of_days' => 14, 'position' => 2],
            'Earned Leave' => ['amount_of_days' => 12, 'position' => 3],
            'Maternity Leave' => ['amount_of_days' => 3, 'position' => 4],
            'Others' => ['amount_of_days' => 5, 'position' => 5],
        ];

        foreach ($leaveTypes as $name => $attributes) {
            LeaveType::updateOrCreate(['name' => $name], $attributes);
        }
    }
}
