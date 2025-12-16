<?php

namespace Willis1776\Notations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Willis1776\Notations\Contracts\Scribe;

/**
 * @property-read Note $note
 * @property-read Scribe $reactor
 */
class NoteReaction extends Model
{
    protected $fillable = [
        'note_id',
        'reactor_id',
        'reactor_type',
        'reaction',
    ];

    public function getTable()
    {
        return Config::getNoteReactionTable();
    }

    /** @return BelongsTo<Note> */
    public function note(): BelongsTo
    {
        return $this->belongsTo(Config::getNoteModel());
    }

    /** @return MorphTo<Scribe> */
    public function reactor(): MorphTo
    {
        return $this->morphTo();
    }
}
