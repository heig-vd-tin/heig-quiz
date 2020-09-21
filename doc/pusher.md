# WebSockets

## Fire events

```php
event(new Event($data));
broadcast(new ShippingStatusUpdated($update))->toOthers();
```

## Broadcast on websockets

```php
class ServerCreated implements ShouldBroadcast // ShouldBroadcastNow
{
    public $update;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function broadcastWhen()
    {
        return true; // Condition to broadcast
    }

    public function broadcastAs()
    {
        return 'server.created'; // alias name, but prepend a dot in Echo
    }

    public function broadcastWith()
    {
        // All public properties are serialized then broadcasted or...
        return []; // If you want your payload
    }

    public function broadcastOn()
    {

        return new PrivateChannel('name.'.$this->update->order_id);
    }
}
```

## Channel Authorization rules

Channels:

- Channel
- PresenceChannels : authorisation + know who is listening to the channel
- PrivateChannels

```php
Broadcast::channel('name.{id}', function ($user, $id) {
    return $user->id === Order::findOrNew($orderId)->user_id;
});
```

### Presence

Echo.join(`chat.${roomId}`)
    .here((users) => {
        //
    })
    .joining((user) => {
        console.log(user.name);
    })
    .leaving((user) => {
        console.log(user.name);
    });

Echo.private('chat')
    .whisper('typing', {
        name: this.user.name
    });

Echo.private('chat')
    .listenForWhisper('typing', (e) => {
        console.log(e.name);
    });

Echo.private(`App.User.${userId}`)
    .notification((notification) => {
        console.log(notification.type);
    });