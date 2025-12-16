<?php

namespace Willis1776\Notations\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Willis1776\Notations\Note;
use Willis1776\Notations\NoteReaction;

class NoteWasReactedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Note            $note,
        public NoteReaction $reaction,
    ) {}
}
