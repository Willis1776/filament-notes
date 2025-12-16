<?php

namespace Willis1776\Notations\Livewire;

use Illuminate\Database\Eloquent\Model;
use Willis1776\Notations\Livewire\Concerns\HasSidebar;
use Livewire\Attributes\On;
use Livewire\Component;

class SubscriptionSidebar extends Component
{
    use HasSidebar;

    public Model $record;

    public function mount(Model $record, ?bool $showSubscribers = null): void
    {
        $this->record = $record;
        $this->mountHasSidebar(true, $showSubscribers);
    }

    #[On('notations:subscription:toggled')]
    public function handleExternalSubscriptionToggle(): void
    {
        $this->refreshSubscribers();
    }

    public function render()
    {
        return view('notations::subscription-sidebar');
    }
}
