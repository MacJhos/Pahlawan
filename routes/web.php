<?php
use App\Http\Controllers\HeroController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HeroController::class, 'index'])->name('home');

Route::get('/galeri', [HeroController::class, 'galeri'])->name('galeri');

Route::get('/about', [HeroController::class, 'about'])->name('about');

Route::get('/pahlawan/{slug}', [HeroController::class, 'show'])->name('hero.show');

Route::get('lang/{lang}', [App\Http\Controllers\LocaleController::class, 'setLocale'])->name('lang.switch');
