<?php

namespace Database\Factories;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'status_id' => TaskStatus::query()->inRandomOrder()->first()->id,
            'created_by_id' => User::query()->inRandomOrder()->first()->id,
            'assigned_to_id' => User::query()->inRandomOrder()->first()->id,
        ];
    }
}
