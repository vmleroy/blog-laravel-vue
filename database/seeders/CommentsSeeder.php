<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('comments_db')->table('comments')->insert([
            [
                'user_id' => 2,
                'post_id' => 1,
                'body' => 'Great post! Very informative and well explained.',
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
            [
                'user_id' => 3,
                'post_id' => 1,
                'body' => 'I agree with my colleague above. Excellent introduction!',
                'created_at' => now()->subDays(17),
                'updated_at' => now()->subDays(17),
            ],
            [
                'user_id' => 1,
                'post_id' => 2,
                'body' => 'Excellent tips! I will apply these practices to my projects.',
                'created_at' => now()->subDays(13),
                'updated_at' => now()->subDays(13),
            ],
            [
                'user_id' => 3,
                'post_id' => 2,
                'body' => 'Very helpful! I already use some of these tips.',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'user_id' => 2,
                'post_id' => 3,
                'body' => 'Excellent guide for beginners! Perfect to start.',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'user_id' => 1,
                'post_id' => 3,
                'body' => 'Vue is really amazing. Great recommendation!',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'user_id' => 3,
                'post_id' => 4,
                'body' => 'I finally understood the difference between authentication and authorization!',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id' => 1,
                'post_id' => 5,
                'body' => 'Microservices are the future! Great explanation.',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ]);
    }
}
