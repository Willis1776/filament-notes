<?php

namespace Willis1776\Notations\Policies;

use Willis1776\Notations\Note;
use Willis1776\Notations\Contracts\Scribe;

class NotePolicy
{
    public function create(Scribe $user): bool
    {
        return true;
    }

    public function update($user, Note $note): bool
    {
        return $note->isAuthor($user);
    }

    public function delete($user, Note $note): bool
    {
        return $note->isAuthor($user);
    }
}
