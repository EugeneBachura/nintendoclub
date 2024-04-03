<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run()
    {
        Level::create([
            'level' => 1,
            'experience_required' => 1000,
            'message' => 'Congratulations! You’ve taken your first step into a larger world. Keep exploring and commenting to reach new heights!',
            'description' => 'This is the base level for a registered user, allowing you to comment on posts.'
        ]);

        Level::create([
            'level' => 2,
            'experience_required' => 5000,
            'message' => 'Well done! You are evolving. Remember, a true Nintendo fan gathers daily rewards as you do now!',
            'description' => 'Starting from this level, you can collect a daily reward for logging in.'
        ]);

        Level::create([
            'level' => 3,
            'experience_required' => 15000,
            'message' => 'Impressive! Your journey is shaping well. Now, you can wield the power of reputation - use it wisely!',
            'description' => 'This level allows you to assign reputation to other users.'
        ]);
    }
}
