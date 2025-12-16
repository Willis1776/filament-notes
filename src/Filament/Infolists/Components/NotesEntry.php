<?php

namespace Willis1776\Notations\Filament\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Willis1776\Notations\Filament\Concerns\HasMentionables;
use Willis1776\Notations\Filament\Concerns\HasPagination;
use Willis1776\Notations\Filament\Concerns\HasPolling;
use Willis1776\Notations\Filament\Concerns\HasSidebar;
use Willis1776\Notations\Filament\Concerns\HasTipTapCssClasses;

class NotesEntry extends Entry
{
    use HasMentionables;
    use HasPagination;
    use HasPolling;
    use HasSidebar;
    use HasTipTapCssClasses;

    protected string $view = 'notations::filament.infolists.components.notes-entry';
}
