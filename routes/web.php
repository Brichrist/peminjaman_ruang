<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Public booking routes (no authentication required)
Route::get('/', [BookingController::class, 'roomSchedule'])->name('bookings.room-schedule');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.check-availability');
Route::get('/rooms', [RoomController::class, 'list'])->name('rooms.list');

Route::middleware('auth')->group(function () {
    // Dashboard - redirect based on user role
    Route::get('/dashboard', function () {
        if (auth()->user()?->isAdmin() ?? null) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('bookings.room-schedule');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/bookings/{booking}/cancel', [AdminController::class, 'cancelBooking'])->name('admin.bookings.cancel');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

        // Room management
        Route::resource('rooms', RoomController::class);
    });
});

require __DIR__ . '/auth.php';
