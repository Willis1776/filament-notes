<?php

namespace Willis1776\Notations\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Willis1776\Notations\Note;
use Willis1776\Notations\Contracts\Scribe;

class UserIsSubscribedToNotableEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public readonly Note $note;

    public readonly Scribe $user;

    public function __construct(Note $note, Scribe $user)
    {
        $this->note = $note;
        $this->user = $user;
    }
}
