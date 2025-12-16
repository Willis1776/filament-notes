<?php

namespace Willis1776\Notations\Livewire;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Willis1776\Notations\Note as NoteModel;
use Willis1776\Notations\Config;
use Willis1776\Notations\Contracts\RenderableNote;
use Willis1776\Notations\Livewire\Concerns\HasMentions;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class Note extends Component
{
    use HasMentions;

    public NoteModel|RenderableNote $note;

    public string $noteBody = '';

    public bool $editing = false;

    public ?string $tipTapCssClasses = null;

    protected $rules = [
        'noteBody' => 'required|string',
    ];

    #[On('note:reaction:toggled')]
    public function handleReactionToggledEvent(string $reaction, int $noteId): void
    {
        if ($this->note->getId() !== $noteId) {
            return;
        }

        $this->toggleReaction($reaction);
    }

    #[Renderless]
    public function delete()
    {
        if (! auth()->user()?->can('delete', $this->note)) {
            return;
        }

        $this->note->delete();

        $this->dispatch('note:deleted');

        Notification::make()
            ->title(__('notations::notes.notification_note_deleted'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('notations::note');
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

        $this->dispatch('note:content:cleared');
    }

    public function edit(): void
    {
        if (! Config::resolveAuthenticatedUser()?->can('update', $this->note)) {
            return;
        }

        $this->editing = true;
        $this->noteBody = $this->note->body;

        $this->dispatch('note:updated');
    }

    public function updateNote()
    {
        if (! Config::resolveAuthenticatedUser()?->can('update', $this->note)) {
            return;
        }

        $this->note->update([
            'body' => $this->noteBody,
        ]);

        $this->editing = false;
    }

    public function cancelEditing()
    {
        $this->editing = false;
        $this->noteBody = '';
    }

    #[Renderless]
    public function toggleReaction(string $reaction): void
    {
        if (! $this->note instanceof NoteModel) {
            return;
        }

        $this->note->toggleReaction($reaction);

        $this->dispatch('note:reaction:saved');
    }

    public function getTipTapCssClasses(): ?string
    {
        return $this->tipTapCssClasses ?? Config::getTipTapCssClasses();
    }
}
