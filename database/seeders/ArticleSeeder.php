<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('articles')->insert([
            'title' => Str::random(10),
            'author' => Str::random(10).'@gmail.com',
            'date_published' => Hash::make('password'),
        ]);
    }
}
