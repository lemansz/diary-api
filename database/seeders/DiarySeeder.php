<?php

namespace Database\Seeders;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiarySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id');

        Diary::factory()
            ->count(8)
            ->create([
            'user_id' => fn () => $userIds->random()
            ]);
    }
}
