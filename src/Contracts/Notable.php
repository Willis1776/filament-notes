<?php

namespace Willis1776\Notations\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
interface Notable
{
    public function notes(): MorphMany;

    /**
     * Get the identifier key for the object. Usually the primary key.
     *
     * @return int|string|null
     */
    public function getKey();

    /**
     * @return string
     */
    public function getMorphClass();
}
