<?php

namespace Willis1776\Notations\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Willis1776\Notations\Livewire\Concerns\HasMentions;
use Willis1776\Notations\Livewire\Concerns\HasPagination;
use Willis1776\Notations\Livewire\Concerns\HasPolling;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class NoteList extends Component
{
    use HasMentions;
    use HasPagination;
    use HasPolling;

    public Model $record;

    public ?string $tipTapCssClasses = null;

    public function render()
    {
        return view('notations::note-list');
    }

    #[Computed]
    public function notes(): Collection
    {
        return $this->record->getNotes($this->paginate ? $this->perPage : null);
    }

    #[On('note:saved')]
    #[On('note:updated')]
    #[On('note:deleted')]
    public function reloadNotes(): void
    {
        unset($this->notes);
    }
}
