<?php

namespace Willis1776\Notations\Livewire;

use Willis1776\Notations\Contracts\RenderableNote;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class RenderableNoteSynth extends Synth
{
    public static $key = 'renderable-note';

    public function dehydrate($target)
    {
        return [[
            //
        ], []];
    }

    public function hydrate($value)
    {
        $instance = new RenderableNote();

        return $instance;
    }

    public static function match($target)
    {
        return $target instanceof RenderableNote;
    }
}
