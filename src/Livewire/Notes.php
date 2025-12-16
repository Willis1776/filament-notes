<?php

namespace Willis1776\Notations\Livewire;

use Illuminate\Database\Eloquent\Model;
use Willis1776\Notations\Actions\SaveNote;
use Willis1776\Notations\Config;
use Willis1776\Notations\Livewire\Concerns\HasMentions;
use Willis1776\Notations\Livewire\Concerns\HasPagination;
use Willis1776\Notations\Livewire\Concerns\HasPolling;
use Willis1776\Notations\Livewire\Concerns\HasSidebar;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class Notes extends Component
{
    use HasMentions;
    use HasPagination;
    use HasPolling;
    use HasSidebar;

    public Model $record;

    public string $noteBody = '';

    public ?string $tipTapCssClasses = null;

    protected $rules = [
        'noteBody' => 'required|string',
    ];

    #[Renderless]
    public function save()
    {
        $this->validate();

        SaveNote::run(
            $this->record,
            Config::resolveAuthenticatedUser(),
            $this->noteBody
        );

        $this->clear();
        $this->dispatch('note:saved');
    }

    public function render()
    {
        return view('notations::notes');
    }

    #[On('body:updated')]
    #[Renderless]
    public function updateNoteBodyContent($value): void
    {
        $this->noteBody = $value;
    }

    #[Renderless]
    public function clear(): void
    {
        $this->noteBody = '';

        $this->dispatch('notes:content:cleared');
    }

    public function getPlaceholder(): string
    {
        return __('notations::notes.placeholder');
    }

    public function getTipTapCssClasses(): ?string
    {
        return $this->tipTapCssClasses ?? Config::getTipTapCssClasses();
    }
}
