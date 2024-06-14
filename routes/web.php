<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\FlashCardController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\VocabularyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{id}', [TagController::class, 'show'])->name('tags.show');
Route::post('/tags/add', [TagController::class, 'store'])->name('tags.store');
Route::post('tags/update', [TagController::class, 'update'])->name('tags.update');
Route::post('tags/delete', [TagController::class, 'destroy'])->name('tags.destroy');

Route::get('/flashcard', [FlashCardController::class, 'index'])->name('flashcard.index');
Route::get('/flashcard/learn', [FlashCardController::class, 'viewLearnFlashCard'])->name('flashcard.learn');
Route::get('/flashcard/test', [FlashCardController::class, 'viewTest'])->name('flashcard.test');
Route::post('/test/add', [TestController::class, 'store'])->name('test.add');

Route::get('/search', [VocabularyController::class, 'searchIndex'])->name('vocabulary.search');
Route::get('/search/{vocab}', [VocabularyController::class, 'searchDetail'])->name('vocabulary.searchDetail');

Route::post('/vocabulary/add', [VocabularyController::class, 'store'])->name('vocabulary.add');

Route::post('/card/update', [CardController::class, 'update'])->name('card.update');

require __DIR__.'/auth.php';
