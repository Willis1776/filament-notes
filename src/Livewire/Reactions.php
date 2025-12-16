<?php

namespace Willis1776\Notations\Livewire;

use Illuminate\Contracts\View\View;
use Willis1776\Notations\Note as NoteModel;
use Willis1776\Notations\Config;
use Willis1776\Notations\Contracts\RenderableNote;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Reactions extends Component
{
    public RenderableNote $note;

    public function handleReactionToggle(string $reaction): void
    {
        $this->dispatch(
            'note:reaction:toggled',
            reaction: $reaction,
            noteId: $this->note->getId()
        )->to(Note::class);

        unset($this->reactionSummary);
    }

    public function render(): View
    {
        return view('notations::reactions', [
            'allowedReactions' => Config::getAllowedReactions(),
        ]);
    }

    #[On('note:reaction:saved')]
    public function refreshReactionSummary()
    {
        unset($this->reactionSummary);
    }

    #[Computed]
    public function reactionSummary()
    {
        if (! $this->note instanceof NoteModel) {
            return [];
        }

        if (! $this->note->relationLoaded('reactions')) {
            $this->note->load('reactions.reactor');
        }

        return $this->note->reactions
            ->groupBy('reaction')
            ->map(function ($group) {
                $user = Config::resolveAuthenticatedUser();

                return [
                    'count' => $group->count(),
                    'reaction' => $group->first()->reaction,
                    'reacted_by_current_user' => $user && $group->contains(fn ($reaction) => $reaction->reactor_id == $user->getKey() && $reaction->reactor_type == $user->getMorphClass()),
                ];
            })
            ->sortByDesc('count')
            ->toArray();
    }
}
