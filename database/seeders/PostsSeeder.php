<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('posts_db')->table('posts')->insert([
            [
                'user_id' => 1,
                'title' => 'Welcome to the Blog!',
                'content' => 'Welcome to our new blog! This is the first post where you can share your ideas and experiences with the community.',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'user_id' => 2,
                'title' => 'Laravel Tips and Tricks',
                'content' => 'Learn the best practices for developing robust applications with Laravel. In this post I will share some tips that can improve your productivity.',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'user_id' => 3,
                'title' => 'Vue.js for Beginners',
                'content' => 'Vue.js is an excellent framework for creating interactive interfaces. Here you will find a complete guide to start your journey with Vue.js.',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id' => 2,
                'title' => 'Authentication and Authorization',
                'content' => 'Understand the difference between authentication and authorization, and how to implement a secure system in your application using JWT tokens.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => 1,
                'title' => 'Microservices Architecture',
                'content' => 'Learn how to structure your application using microservices, making code scalability and maintenance easier.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);
    }
}
