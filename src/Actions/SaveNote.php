<?php

namespace Willis1776\Notations\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Willis1776\Notations\Note;
use Willis1776\Notations\Config;
use Willis1776\Notations\Contracts\Scribe;
use Willis1776\Notations\Events\NoteWasCreatedEvent;
use Willis1776\Notations\Events\UserIsSubscribedToNotableEvent;
use Willis1776\Notations\Events\UserWasMentionedEvent;

class SaveNote
{
    /**
     * @throws AuthorizationException
     */
    public function __invoke(Model $notable, Scribe $author, string $body): Note
    {
        if ($author->cannot('create', Config::getNoteModel())) {
            throw new AuthorizationException('Cannot create note');
        }

        $note = $notable->notes()->create([
            'body' => $body,
            'author_id' => $author->getKey(),
            'author_type' => $author->getMorphClass(),
        ]);

        $this->dispatchEvents($note);

        return $note;
    }

    protected function dispatchEvents(Note $note): void
    {
        if ($note->wasRecentlyCreated) {
            NoteWasCreatedEvent::dispatch($note);
        }

        $mentionees = $note->getMentioned();

        $mentionees->each(function ($mentionee) use ($note) {
            UserWasMentionedEvent::dispatch($note, $mentionee);
        });

        if (config('notations.subscriptions.auto_subscribe_on_mention', true)
            && method_exists($note->commentable, 'subscribe')
        ) {
            $mentionees->each(function (Scribe $mentionee) use ($note) {
                $note->commentable->subscribe($mentionee);
            });
        }

        $subscribers = method_exists($note->notable, 'getSubscribers')
            ? $note->notable->getSubscribers()
            : collect();

        if ($subscribers->isNotEmpty()) {
            $excludeIds = collect([$note->author_id])
                ->merge($mentionees->map(fn ($u) => $u->getKey()))
                ->unique()
                ->all();

            $subscribers
                ->filter(fn ($subscriber) => ! in_array($subscriber->getKey(), $excludeIds, true))
                ->each(function (Scribe $subscriber) use ($note) {
                    if (config('notations.subscriptions.dispatch_as_mention', false)) {
                        UserWasMentionedEvent::dispatch($note, $subscriber);
                    } else {
                        UserIsSubscribedToNotableEvent::dispatch($note, $subscriber);
                    }
                });
        }

        if (config('notations.subscriptions.auto_subscribe_on_comment', true)
            && method_exists($note->notable, 'subscribe')
        ) {
            // Only subscribe if not already subscribed
            if (method_exists($note->notable, 'isSubscribed')) {
                if (! $note->notable->isSubscribed($note->author)) {
                    $note->notable->subscribe($note->author);
                }
            } else {
                $note->notable->subscribe($note->author);
            }
        }
    }

    public static function run(...$args)
    {
        return (new static())(...$args);
    }
}
