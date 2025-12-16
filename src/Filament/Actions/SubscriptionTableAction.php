<?php

namespace Willis1776\Notations\Filament\Actions;

use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Willis1776\Notations\Filament\Concerns\HasSidebar;

class SubscriptionTableAction extends Action
{
    use HasSidebar;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn (Model $record): string => $this->computeSubscriptionLabel($record))
            ->icon(fn (Model $record): string => $this->computeSubscriptionIcon($record))
            ->color(fn (Model $record): string => $this->computeSubscriptionColor($record, 'table'))
            ->action(function (Model $record) {
                $subscribed = $this->toggleSubscriptionForRecord($record);

                if ($subscribed === null) {
                    return;
                }

                $this->successNotificationTitle(
                    $subscribed ? __('notations::notes.notification_subscribed') : __('notations::notes.notification_unsubscribed')
                );

                $this->success();
            })
            ->requiresConfirmation(false);
    }

    public static function getDefaultName(): ?string
    {
        return 'subscriptionList';
    }
}
