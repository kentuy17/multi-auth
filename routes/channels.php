<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Transactions;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('user.{id}', function($user, $betUserId) {
    if($user->isOnline() ) {
        return $user->id == $betUserId;
    }
    // return false;
});

Broadcast::channel('cashin.{processedBy}', function ($user, $processedBy) {
    $trans = Transactions::where('processedBy', $processedBy)
        ->where('action', 'deposit')
        ->where('status', 'pending')
        ->orderBy('id', 'desc')
        ->first();
    if($trans) {
        return $user->id == $trans->processedBy;
    }
    return false;
});
