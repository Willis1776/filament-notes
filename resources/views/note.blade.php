@use('\Kirschbaum\Commentions\Config')

<div class="comm:flex comm:items-start comm:gap-x-4 comm:border comm:border-gray-300 comm:dark:border-gray-700 comm:p-4 comm:rounded-lg comm:shadow-sm comm:mb-2" id="filament-note-{{ $note->getId() }}">
    @if ($avatar = $note->getAuthorAvatar())
        <img
            src="{{ $note->getAuthorAvatar() }}"
            alt="{{ __('notations::notes.user_avatar_alt') }}"
            class="comm:w-10 comm:h-10 comm:rounded-full comm:mt-0.5 comm:object-cover comm:object-center"
        />
    @else
        <div class="comm:w-10 comm:h-10 comm:rounded-full comm:mt-0.5 "></div>
    @endif

    <div class="comm:flex-1">
        <div class="comm:text-sm comm:font-bold comm:text-gray-900 comm:dark:text-gray-100 comm:flex comm:justify-between comm:items-center">
            <div>
                {{ $note->getAuthorName() }}
                <span
                    class="comm:text-xs comm:text-gray-500 comm:dark:text-gray-300"
                    title="{{ __('notations::notes.noted_at', ['datetime' => $note->getCreatedAt()->format('Y-m-d H:i:s')]) }}"
                >{{ $note->getCreatedAt()->diffForHumans() }}</span>

                @if ($note->getUpdatedAt()->gt($note->getCreatedAt()))
                    <span
                        class="comm:text-xs comm:text-gray-300 comm:ml-1"
                        title="{{ __('notations::notes.edited_at', ['datetime' => $note->getUpdatedAt()->format('Y-m-d H:i:s')]) }}"
                    >({{ __('notations::notes.edited') }})</span>
                @endif

                @if ($note->getLabel())
                    <span class="comm:text-xs comm:text-gray-500 comm:dark:text-gray-300 comm:bg-gray-100 comm:dark:bg-gray-800 comm:px-1.5 comm:py-0.5 comm:rounded-md">
                        {{ $note->getLabel() }}
                    </span>
                @endif
            </div>

            @if ($note->isNote() && Config::resolveAuthenticatedUser()?->canAny(['update', 'delete'], $note))
                <div class="comm:flex comm:gap-x-1">
                    @if (Config::resolveAuthenticatedUser()?->can('update', $note))
                        <x-filament::icon-button
                            icon="heroicon-s-pencil-square"
                            wire:click="edit"
                            size="xs"
                            color="gray"
                        />
                    @endif

                    @if (Config::resolveAuthenticatedUser()?->can('delete', $note))
                        <x-filament::modal
                            id="delete-note-modal-{{ $note->getId() }}"
                            width="sm"
                        >
                            <x-slot name="trigger">
                                <x-filament::icon-button
                                    icon="heroicon-s-trash"
                                    size="xs"
                                    color="gray"
                                />
                            </x-slot>

                            <x-slot name="heading">
                                {{ __('notations::notes.delete_note_heading') }}
                            </x-slot>

                            <div class="comm:py-4">
                                {{ __('notations::notes.delete_note_body') }}
                            </div>

                            <x-slot name="footer">
                                <div class="comm:flex comm:justify-end comm:gap-x-4">
                                    <x-filament::button
                                        wire:click="$dispatch('close-modal', { id: 'delete-note-modal-{{ $note->getId() }}' })"
                                        color="gray"
                                    >
                                        {{ __('notations::notes.cancel') }}
                                    </x-filament::button>

                                    <x-filament::button
                                        wire:click="delete"
                                        color="danger"
                                    >
                                        {{ __('notations::notes.delete') }}
                                    </x-filament::button>
                                </div>
                            </x-slot>
                        </x-filament::modal>
                    @endif
                </div>
            @endif
        </div>

        @if ($editing)
            <div class="comm:mt-2">
                <div class="tip-tap-container comm:mb-2" wire:ignore>
                    <div x-data="editor(@js($noteBody), @js($mentionables), 'note', null, @js($this->getTipTapCssClasses()))">
                        <div x-ref="element"></div>
                    </div>
                </div>

                <div class="comm:flex comm:gap-x-2">
                    <x-filament::button
                        wire:click="updateComment({{ $note->getId() }})"
                        size="sm"
                    >
                        {{ __('notations::notes.save') }}
                    </x-filament::button>

                    <x-filament::button
                        wire:click="cancelEditing"
                        size="sm"
                        color="gray"
                    >
                        {{ __('notations::notes.cancel') }}
                    </x-filament::button>
                </div>
            </div>
        @else
            <div class="comm:mt-1 comm:space-y-6 comm:text-sm comm:text-gray-800 comm:dark:text-gray-200">{!! $note->getParsedBody() !!}</div>

            @if ($note->isNote())
                <livewire:notations::reactions
                    :note="$note"
                    :wire:key="'reaction-manager-' . $note->getId()"
                />
            @endif
        @endif
    </div>
</div>
