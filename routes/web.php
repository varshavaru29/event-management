<?php

use App\Http\Controllers\AttendingEventController;
use App\Http\Controllers\DeleteCommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LikeSystemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreCommentController;
use App\Http\Controllers\TicketPurchaseController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/search-event', [WelcomeController::class, 'searchEvent'])->name('search');
Route::get('/event/{id}', [WelcomeController::class, 'eventShow'])->name('eventShow');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});
Route::middleware('auth','role:organizer,attendee')->group(function () {
    Route::post('/events/{id}/comments', StoreCommentController::class)->name('events.comments');
    Route::delete('/events/{id}/comments/{comment}', DeleteCommentController::class)->name('events.comments.destroy');
    Route::post(
        '/events-like/{id}',
        LikeSystemController::class
    )->name('events.like');
    Route::post('/tickets/{ticket}/purchase', TicketPurchaseController::class)->name('tickets.purchase');


});
Route::middleware('auth','role:organizer')->group(function () {
    Route::resource('/events', EventController::class);
    Route::get('/attendind-events', AttendingEventController::class)->name('attendingEvents');
});

require __DIR__ . '/auth.php';
