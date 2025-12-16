<?php

namespace Willis1776\Notations;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Willis1776\Notations\Note as NoteModel;
use Willis1776\Notations\Events\UserWasMentionedEvent;
use Willis1776\Notations\Listeners\SendUserMentionedNotification;
use Willis1776\Notations\Livewire\Note;
use Willis1776\Notations\Livewire\NoteList;
use Willis1776\Notations\Livewire\Notes;
use Willis1776\Notations\Livewire\Reactions;
use Willis1776\Notations\Livewire\SubscriptionSidebar;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NotationsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'notations';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasMigrations([
                'create_notations_tables',
                'create_notations_subscriptions_table',
            ]);
    }

    public function packageBooted(): void
    {
        Livewire::component('notations::note', Note::class);
        Livewire::component('notations::note-list', NoteList::class);
        Livewire::component('notations::notes', Notes::class);
        Livewire::component('notations::reactions', Reactions::class);
        Livewire::component('notations::subscription-sidebar', SubscriptionSidebar::class);

        FilamentAsset::register(
            [
                Js::make('notations-scripts', __DIR__ . '/../resources/dist/notations.js'),
            ],
            'willis1776/' . static::$name
        );

        FilamentAsset::register(
            [
                Css::make('notations', __DIR__ . '/../resources/dist/notations.css'),
            ],
            'willis1776/' . static::$name
        );

        Gate::policy(NoteModel::class, config('notations.note.policy'));

        // Allow publishing of translation files with a custom tag
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/notations'),
        ], 'notations-lang');

        if (config('notations.notifications.mentions.enabled', false)) {
            $listenerClass = (string) config('notations.notifications.mentions.listener', SendUserMentionedNotification::class);
            Event::listen(UserWasMentionedEvent::class, $listenerClass);
        }
    }
}
