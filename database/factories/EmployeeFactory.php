<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => 'EMP-'.$this->faker->unique()->numerify('####'),
            'name' => $this->faker->name(),
            'designation' => $this->faker->jobTitle(),
            'department' => $this->faker->randomElement([
                'Engineering', 'Human Resources', 'Finance', 'Marketing', 'Sales', 'Operations', 'Support',
            ]),
            'date_of_joining' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'dismissed_at' => null,
        ];
    }
}
