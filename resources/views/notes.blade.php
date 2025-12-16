@use('\Willis1776\Notations\Config')

<div class="comm:flex comm:gap-4 comm:h-full" x-data="{ wasFocused: false }">
    {{-- Main Notes Area --}}
    <div class="comm:flex-1 comm:space-y-2">
        @if (Config::resolveAuthenticatedUser()?->can('create', Config::getNoteModel()))
            <form wire:submit.prevent="save" x-cloak>
                {{-- tiptap editor --}}
                <div class="comm:relative tip-tap-container comm:mb-2" x-on:click="wasFocused = true" wire:ignore>
                    <div
                        x-data="editor(@js($noteBody), @js($this->mentions), 'notes', @js($this->getPlaceholder()), @js($this->getTipTapCssClasses()))"
                    >
                        <div x-ref="element"></div>
                    </div>
                </div>

            <template x-if="wasFocused">
                <div>
                    <x-filament::button
                        wire:click="save"
                        size="sm"
                    >{{ __('notations::notes.note') }}</x-filament::button>

                    <x-filament::button
                        x-on:click="wasFocused = false"
                        wire:click="clear"
                        size="sm"
                        color="gray"
                    >{{ __('notations::notes.cancel') }}</x-filament::button>
                </div>
            </template>
        </form>
    @endif

        <livewire:notations::note-list
            :record="$record"
            :mentionables="$this->mentions"
            :polling-interval="$pollingInterval"
            :paginate="$paginate ?? true"
            :per-page="$perPage ?? 5"
            :load-more-label="$loadMoreLabel ?? __('notations::notes.show_more')"
            :per-page-increment="$perPageIncrement ?? null"
            :tip-tap-css-classes="$tipTapCssClasses"
        />
    </div>

    {{-- Subscription Sidebar --}}
    @if ($this->canSubscribe && $this->resolvedSidebarEnabled)
        <livewire:notations::subscription-sidebar
            :record="$record"
            :show-subscribers="$this->resolvedShowSubscribers"
        />
    @endif
</div>
