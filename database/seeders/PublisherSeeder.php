<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publisher = new Publisher();
        $publisher->name = 'El templo del Libro';
        $publisher->country = 'España';
        $publisher->website = 'templolibro.com';
        $publisher->email = 'correo@templolibro.com';
        $publisher->description = 'Dedicado a libros de informática y otros';
        $publisher->save();

        $publisher = new Publisher();
        $publisher->name = 'Otwil RPL';
        $publisher->country = 'USA';
        $publisher->website = 'houserpl.net';
        $publisher->email = 'email@houserpl.net';
        $publisher->description = 'Dedicado a libros de general';
        $publisher->save();
    }
}
