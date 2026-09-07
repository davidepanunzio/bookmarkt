<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;

test('il catalogo filtra i libri per categoria', function () {
    $narrativa = Category::factory()->create(['name' => 'Narrativa']);
    $giallo = Category::factory()->create(['name' => 'Giallo']);

    $libroNarrativa = Book::factory()->create(['category_id' => $narrativa->id]);
    $libroGiallo = Book::factory()->create(['category_id' => $giallo->id]);

    $response = $this->get(route('books.index', ['categoria' => $giallo->slug]));

    $response->assertSee($libroGiallo->title);
    $response->assertDontSee($libroNarrativa->title);
});

test('il catalogo filtra i libri per fascia di prezzo', function () {
    $economico = Book::factory()->create(['title' => 'Libro Economico', 'price' => 5.00]);
    $costoso = Book::factory()->create(['title' => 'Libro Costoso', 'price' => 50.00]);

    $response = $this->get(route('books.index', ['prezzo_min' => 1, 'prezzo_max' => 10]));

    $response->assertSee('Libro Economico');
    $response->assertDontSee('Libro Costoso');
});

test('il catalogo mostra solo i libri disponibili quando richiesto', function () {
    $disponibile = Book::factory()->create(['title' => 'Libro Disponibile', 'stock' => 5]);
    $esaurito = Book::factory()->create(['title' => 'Libro Esaurito', 'stock' => 0]);

    $response = $this->get(route('books.index', ['disponibili' => 1]));

    $response->assertSee('Libro Disponibile');
    $response->assertDontSee('Libro Esaurito');
});

test('la ricerca trova i libri anche cercando il nome dell\'autore', function () {
    $autore = Author::factory()->create(['name' => 'Umberto Eco']);
    $libro = Book::factory()->create(['title' => 'Il nome della rosa', 'author_id' => $autore->id]);

    $response = $this->get(route('books.index', ['cerca' => 'Eco']));

    $response->assertSee($libro->title);
});
