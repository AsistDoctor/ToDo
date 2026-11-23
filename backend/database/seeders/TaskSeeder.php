<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create(['title' => 'Разобраться, что такое роут']);
        Task::create(['title' => 'Понять, как работает миграция', 'done' => true]);
        Task::create(['title' => 'Открыть TaskController и прочитать метод update()']);
    }
}
