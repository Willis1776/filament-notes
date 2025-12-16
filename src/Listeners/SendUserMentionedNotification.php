<?php

namespace Willis1776\Notations\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Willis1776\Notations\Events\UserWasMentionedEvent;
use Willis1776\Notations\Notifications\UserMentionedInNote;

class SendUserMentionedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserWasMentionedEvent $event): void
    {
        $user = $event->user;

        if (! config('notations.notifications.mentions.enabled', false)) {
            return;
        }

        $channels = (array) config('notations.notifications.mentions.channels', []);
        if (empty($channels)) {
            return;
        }

        $notificationClass = (string) config('notations.notifications.mentions.notification', UserMentionedInNote::class);
        $notification = app($notificationClass, ['note' => $event->note, 'channels' => $channels]);

        Notification::send($user, $notification);
    }
}
