<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chamar os seeders para os microsserviços
        $this->call([
            AuthSeeder::class,
            RbacSeeder::class,
            PostsSeeder::class,
            CommentsSeeder::class,
        ]);
    }
}
