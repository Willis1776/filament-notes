<?php

namespace Willis1776\Notations\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Willis1776\Notations\Note;
use Willis1776\Notations\Config;
use Willis1776\Notations\Manager;

class UserMentionedInNote extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Note  $note,
        protected array $channels
    ) {}

    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = Config::resolveNoteUrl($this->note) ?? url('/');

        return (new MailMessage())
            ->subject((string) config('notations.notifications.mentions.mail.subject', 'You were mentioned in a note'))
            ->greeting('Hi ' . Manager::getName($notifiable))
            ->line('You were mentioned in a note by ' . $this->note->getAuthorName() . '.')
            ->line(strip_tags($this->note->getBodyMarkdown()))
            ->action('View note', $url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'note_id' => $this->note->getId(),
            'note_body' => $this->note->getBody(),
            'author_name' => $this->note->getAuthorName(),
            'url' => Config::resolveNoteUrl($this->note),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
