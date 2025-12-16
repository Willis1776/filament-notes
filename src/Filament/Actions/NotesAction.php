<?php

namespace Willis1776\Notations\Filament\Actions;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Willis1776\Notations\Filament\Concerns\HasMentionables;
use Willis1776\Notations\Filament\Concerns\HasPagination;
use Willis1776\Notations\Filament\Concerns\HasPolling;
use Willis1776\Notations\Filament\Concerns\HasSidebar;
use Willis1776\Notations\Filament\Concerns\HasTipTapCssClasses;

class NotesAction extends Action
{
    use HasMentionables;
    use HasPagination;
    use HasPolling;
    use HasSidebar;
    use HasTipTapCssClasses;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-o-chat-bubble-left-right')
            ->modalContent(fn (Model $record) => view('notations::notes-modal', [
                'record' => $record,
                'mentionables' => $this->getMentionables(),
                'pollingInterval' => $this->getPollingInterval(),
                'paginate' => $this->isPaginated(),
                'perPage' => $this->getPerPage(),
                'loadMoreLabel' => $this->getLoadMoreLabel(),
                'perPageIncrement' => $this->getPerPageIncrement() ?: $this->getPerPage(),
                'sidebarEnabled' => $this->isSidebarEnabled(),
                'showSubscribers' => $this->showSubscribers(),
                'tipTapCssClasses' => $this->getTipTapCssClasses(),
            ]))
            ->modalWidth($this->isSidebarEnabled() ? '4xl' : 'xl')
            ->label(__('notations::notes.label'))
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->modalAutofocus(false);
    }

    public static function getDefaultName(): ?string
    {
        return 'notes';
    }
}
