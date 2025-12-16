<?php

namespace Willis1776\Notations\Actions;

use Willis1776\Notations\Note;
use Willis1776\Notations\NoteReaction;
use Willis1776\Notations\Config;
use Willis1776\Notations\Contracts\Scribe;
use Willis1776\Notations\Events\NoteWasReactedEvent;

class ToggleNoteReaction
{
    public static function run(Note $note, string $reaction, ?Scribe $user = null): void
    {
        if (! $user) {
            return;
        }

        if (! in_array($reaction, Config::getAllowedReactions())) {
            return;
        }

        /** @var NoteReaction $existingReaction */
        $existingReaction = $note
            ->reactions()
            ->where('reactor_id', $user->getKey())
            ->where('reactor_type', $user->getMorphClass())
            ->where('reaction', $reaction)
            ->first();

        if ($existingReaction) {
            $existingReaction->delete();
        } else {
            $reaction = $note->reactions()->create([
                'reactor_id' => $user->getKey(),
                'reactor_type' => $user->getMorphClass(),
                'reaction' => $reaction,
            ]);

            event(new NoteWasReactedEvent(
                note: $note,
                reaction: $reaction,
            ));
        }
    }
}
