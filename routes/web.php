<?php

use App\Events\MessageSent;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\ChatMessageController;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum','verified'])->get('/chat',function(){
    return Inertia::render('Chat/containere');
})->name('chat');


Route::middleware('auth:sanctum')->get('/chat/rooms',[ChatMessageController::class,'rooms']);
Route::middleware('auth:sanctum')->get('/chat/room/{roomId}/messages',[ChatMessageController::class,'messages']);
Route::middleware('auth:sanctum')->post('/chat/room/{roomId}/message',[ChatMessageController::class,'newMessage']);


Route::post('/broadcast/message', [BroadcastController::class, 'sendMessage']);
Route::post('/broadcast/user/{userId}', [BroadcastController::class, 'sendToUser']);

Route::get('/test-broadcast', function () {
    // Test using Laravel's built-in broadcasting
    event(new MessageSent('Test message', auth()->user() ?? 'Guest'));

    // Test using our custom class
    $pusherBroadcast = app(\App\Broadcasting\PusherBroadcast::class);
    $pusherBroadcast->send('chat-room', 'test.event', [
        'message' => 'Test from custom broadcaster',
        'time' => now()
    ]);

    return 'Broadcast test completed!';
});