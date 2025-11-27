<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('task_statuses')->insert([
            ['name' => 'Новый'],
            ['name' => 'В работе'],
            ['name' => 'На тестировании'],
            ['name' => 'Завершен']
        ]);
    }
}
