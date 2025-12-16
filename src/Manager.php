<?php

namespace Willis1776\Notations;

use Filament\Models\Contracts\HasName;
use Willis1776\Notations\Contracts\Scribe;

class Manager
{
    public static function getName(Scribe $mentionable)
    {
        if (method_exists($mentionable, 'getScribeName')) {
            return call_user_func_array([$mentionable, 'getScribeName'], []);
        }

        if ($mentionable instanceof HasName) {
            return $mentionable->getFilamentName();
        }

        return $mentionable->name;
    }
}
