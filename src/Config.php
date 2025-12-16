<?php

namespace Willis1776\Notations;

use Closure;
use InvalidArgumentException;
use Willis1776\Notations\Contracts\Scribe;

class Config
{
    protected static ?string $guard = null;

    protected static ?Closure $resolveAuthenticatedUser = null;

    protected static ?Closure $resolveNoteUrl = null;

    protected static ?Closure $resolveTipTapCssClasses = null;

    public static function resolveAuthenticatedUserUsing(Closure $callback): void
    {
        static::$resolveAuthenticatedUser = $callback;
    }

    public static function resolveAuthenticatedUser(): ?Scribe
    {
        $resolver = static::$resolveAuthenticatedUser;
        $user = $resolver ? call_user_func($resolver) : auth()->guard(static::$guard)->user();

        if ($user !== null && ! ($user instanceof Scribe)) {
            throw new InvalidArgumentException('Resolved user must implement ' . Scribe::class);
        }

        return $user;
    }

    public static function getNoteTable(): string
    {
        return config('notations.tables.notes', 'notes');
    }

    public static function getNoteReactionTable(): string
    {
        return config('notations.tables.note_reactions', 'note_reactions');
    }

    public static function getNoteSubscriptionTable(): string
    {
        return config('notations.tables.note_subscriptions', 'note_subscriptions');
    }

    public static function resolveNoteUrlUsing(Closure $callback): void
    {
        static::$resolveNoteUrl = $callback;
    }

    public static function resolveNoteUrl(?Note $comment): ?string
    {
        if ($comment === null) {
            return null;
        }

        if (static::$resolveNoteUrl instanceof Closure) {
            return call_user_func(static::$resolveNoteUrl, $comment);
        }

        return null;
    }

    public static function getNoteModel(): string
    {
        return config('notations.comment.model', Note::class);
    }

    public static function getScribeModel(): string
    {
        return config('notations.scribe.model');
    }

    public static function getAllowedReactions(): array
    {
        return config('notations.reactions.allowed', ['👍']);
    }

    public static function resolveTipTapCssClassesUsing(Closure $callback): void
    {
        static::$resolveTipTapCssClasses = $callback;
    }

    public static function getTipTapCssClasses(): ?string
    {
        if (static::$resolveTipTapCssClasses instanceof Closure) {
            return call_user_func(static::$resolveTipTapCssClasses);
        }

        return 'comm:prose comm:dark:prose-invert comm:prose-sm comm:sm:prose-base comm:lg:prose-lg comm:xl:prose-2xl comm:focus:outline-none comm:p-4 comm:min-w-full comm:w-full comm:rounded-lg comm:border comm:border-gray-300 comm:dark:border-gray-700';
    }
}
