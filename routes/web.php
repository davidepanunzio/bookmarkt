<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Pagine informative statiche, linkate dal footer
Route::view('/privacy', 'legal.privacy')->name('legal.privacy');
Route::view('/termini', 'legal.termini')->name('legal.termini');

// Home page pubblica: libro in evidenza + ultimi arrivi
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalogo pubblico: visibile anche senza effettuare il login
Route::get('/libri', [BookController::class, 'index'])->name('books.index');
Route::get('/libri/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware('auth')->group(function () {
    Route::get('/profilo', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profilo', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profilo', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Carrello personale dell'utente autenticato
    Route::get('/carrello', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrello/{book}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/carrello/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/carrello/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout e storico ordini
    Route::get('/ordini', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/ordini', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/ordini/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Preferiti (wishlist)
    Route::get('/preferiti', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/preferiti/{book}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/preferiti/{book}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Recensioni sui libri
    Route::post('/libri/{book}/recensioni', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/recensioni/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Segnalazioni: l'utente contatta gli amministratori e consulta lo storico delle proprie
    Route::get('/contattaci', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/contattaci/nuova', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/contattaci', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/contattaci/{report}', [ReportController::class, 'show'])->name('reports.show');
});

// Area di gestione del catalogo: riservata agli utenti con ruolo "admin"
// URL in italiano, ma nomi di rotta in inglese (standard Laravel) per coerenza col resto del codice
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('libri', BookController::class)
        ->except(['index', 'show'])
        ->parameters(['libri' => 'book'])
        ->names('books');

    Route::resource('categorie', CategoryController::class)
        ->except(['show'])
        ->parameters(['categorie' => 'category'])
        ->names('categories');

    Route::resource('autori', AuthorController::class)
        ->except(['show'])
        ->parameters(['autori' => 'author'])
        ->names('authors');

    Route::resource('ordini', AdminOrderController::class)
        ->only(['index', 'show', 'update'])
        ->parameters(['ordini' => 'order'])
        ->names('orders');

    Route::get('/recensioni', [AdminReviewController::class, 'index'])->name('reviews.index');

    Route::resource('segnalazioni', AdminReportController::class)
        ->only(['index', 'show', 'update'])
        ->parameters(['segnalazioni' => 'report'])
        ->names('reports');

    Route::resource('utenti', AdminUserController::class)
        ->only(['index', 'show', 'update'])
        ->parameters(['utenti' => 'user'])
        ->names('users');
});

require __DIR__.'/auth.php';
