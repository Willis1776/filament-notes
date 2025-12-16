<?php

namespace Willis1776\Notations\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Willis1776\Notations\Note;

class NoteWasCreatedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Note $note,
    ) {}
}
