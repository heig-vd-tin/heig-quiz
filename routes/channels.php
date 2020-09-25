<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\ActivityChannel;

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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('activity', ActivityChannel::class);

Broadcast::channel('activity.{activity_id}', function ($user, $activity_id) {
    if ($user->canJoinActivity($activity_id)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});
