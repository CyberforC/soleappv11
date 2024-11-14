<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Note;
use App\Models\Profile;
use App\Models\Publisher;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
        Storage::deleteDirectory('public/images');
        Storage::makeDirectory('public/images');

        $this->call(GenreSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PublisherSeeder::class);

        Publisher::factory(5)->create();
        Author::factory()->has(Book::factory()->count(3))->create();
        Author::factory()->has(Book::factory()->count(2))->create();
        Author::factory()->has(Book::factory()->count(2))->create();
        Author::factory()->has(Book::factory()->count(1))->create();
        Profile::factory(4)->create();
        Image::factory(2)->create();
        Note::factory(10)->create();
        User::factory()->hasAttached(Author::factory()->count(2), ['number_star' => rand(1, 5)])->create();
        User::factory()->hasAttached(Book::factory()->count(2), ['number_star' => rand(1, 5)])->create();
        Genre::factory(5)->create();
    }
}
