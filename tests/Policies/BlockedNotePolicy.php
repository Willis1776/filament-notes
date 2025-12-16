<?php

namespace Tests\Policies;

use Kirschbaum\Commentions\Note;
use Kirschbaum\Commentions\Contracts\Commenter;
use Kirschbaum\Commentions\Policies\NotePolicy;

class BlockedNotePolicy extends NotePolicy
{
    public function create(Commenter $user): bool
    {
        return false;
    }

    public function update($user, Note $comment): bool
    {
        return false;
    }

    public function delete($user, Note $comment): bool
    {
        return false;
    }
}
