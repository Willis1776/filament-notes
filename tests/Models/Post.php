<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\Commentions\Contracts\Notable;
use Kirschbaum\Commentions\HasNotes;
use Tests\Database\Factories\PostFactory;

class Post extends Model implements Notable
{
    use HasNotes;
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): PostFactory
    {
        return new PostFactory();
    }
}
