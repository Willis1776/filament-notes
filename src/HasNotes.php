<?php

namespace Willis1776\Notations;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Willis1776\Notations\Actions\SaveNote;
use Willis1776\Notations\Contracts\Scribe;

trait HasNotes
{
    public function notes(): MorphMany
    {
        return $this->morphMany(Config::getScribeModel(), 'notable');
    }

    public function notesQuery(): MorphMany
    {
        return $this->notes()
            ->latest()
            ->with(['author', 'reactions.reactor']);
    }

    public function note(string $body, ?Scribe $author): Note
    {
        return SaveNote::run($this, $author, $body);
    }

    public function getNotes(?int $limit = null): Collection
    {
        if ($limit) {
            return $this->notesQuery()->limit($limit)->get();
        }

        return $this->notesQuery()->get();
    }

    public function subscribe(Scribe $subscriber): void
    {
        NoteSubscription::query()->firstOrCreate([
            'subscribable_type' => $this->getMorphClass(),
            'subscribable_id' => $this->getKey(),
            'subscriber_type' => $subscriber->getMorphClass(),
            'subscriber_id' => $subscriber->getKey(),
        ]);
    }

    public function unsubscribe(Scribe $subscriber): void
    {
        NoteSubscription::query()->where([
            'subscribable_type' => $this->getMorphClass(),
            'subscribable_id' => $this->getKey(),
            'subscriber_type' => $subscriber->getMorphClass(),
            'subscriber_id' => $subscriber->getKey(),
        ])->delete();
    }

    public function isSubscribed(Scribe $subscriber): bool
    {
        return NoteSubscription::query()->where([
            'subscribable_type' => $this->getMorphClass(),
            'subscribable_id' => $this->getKey(),
            'subscriber_type' => $subscriber->getMorphClass(),
            'subscriber_id' => $subscriber->getKey(),
        ])->exists();
    }

    /**
     * @return Collection<int, Scribe>
     */
    public function getSubscribers(): Collection
    {
        $scribeModel = Config::getScribeModel();

        return NoteSubscription::query()
            ->where('subscribable_type', $this->getMorphClass())
            ->where('subscribable_id', $this->getKey())
            ->get()
            ->map(function (NoteSubscription $subscription) use ($scribeModel) {
                return $scribeModel::whereKey($subscription->subscriber_id)->first();
            })
            ->filter();
    }
}
