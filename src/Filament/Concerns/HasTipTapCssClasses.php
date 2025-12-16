<?php

namespace Willis1776\Notations\Filament\Concerns;

use Closure;
use Willis1776\Notations\Config;

trait HasTipTapCssClasses
{
    protected string|Closure|null $tipTapCssClasses = null;

    public function tipTapCssClasses(string|Closure|null $classes): static
    {
        $this->tipTapCssClasses = $classes;

        return $this;
    }

    public function getTipTapCssClasses(): ?string
    {
        return $this->evaluate($this->tipTapCssClasses) ?? Config::getTipTapCssClasses();
    }
}
