<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'name',
        'designation',
        'department',
        'date_of_joining',
        'dismissed_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_joining' => 'date',
            'dismissed_at' => 'date',
        ];
    }

    public function leaveRegisters(): HasMany
    {
        return $this->hasMany(LeaveRegister::class, 'employee_id', 'employee_id');
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(EmployeeLeaveBalance::class, 'employee_id', 'employee_id');
    }
}
