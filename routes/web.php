<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\VisitorBookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/the-course', [SiteController::class, 'course'])->name('course');
Route::get('/the-club', [SiteController::class, 'club'])->name('club');
Route::get('/visit', [SiteController::class, 'visit'])->name('visit');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'sendEnquiry'])->name('contact.send');
Route::get('/book', [VisitorBookController::class, 'index'])->name('book');
Route::post('/book/hold', [VisitorBookController::class, 'hold'])->middleware('throttle:20,1')->name('book.hold');
Route::get('/book/checkout/{token}', [VisitorBookController::class, 'checkout'])->name('book.checkout');
Route::post('/book/checkout/{token}', [VisitorBookController::class, 'complete'])->middleware('throttle:20,1')->name('book.complete');
Route::post('/book/checkout/{token}/cancel', [VisitorBookController::class, 'cancel'])->middleware('throttle:20,1')->name('book.cancel');
Route::get('/book/confirmed', [VisitorBookController::class, 'confirmed'])->name('book.confirmed');
