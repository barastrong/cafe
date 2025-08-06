<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminPanelController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/admin', [AdminPanelController::class, 'index'])->name('index');
    Route::get('/menus', [AdminPanelController::class, 'menusIndex'])->name('menus');
    Route::post('/menus', [AdminPanelController::class, 'menusStore'])->name('menus.store');
    Route::get('/products', [AdminPanelController::class, 'productsIndex'])->name('products');
    Route::post('/products', [AdminPanelController::class, 'productsStore'])->name('products.store');
    Route::get('/promos', [AdminPanelController::class, 'promosIndex'])->name('promos');
    Route::post('/promos', [AdminPanelController::class, 'promosStore'])->name('promos.store');
    Route::get('/chat/{conversation?}', [AdminPanelController::class, 'chatIndex'])->name('chat');
    Route::post('/chat', [AdminPanelController::class, 'chatStore'])->name('chat.store');


});

Route::middleware(['auth', 'chasier'])->group(function () {});

require __DIR__.'/auth.php';
