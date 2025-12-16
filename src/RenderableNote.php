<?php

namespace Willis1776\Notations;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTime;
use Willis1776\Notations\Contracts\RenderableNote as RenderableNoteContract;
use Livewire\Wireable;

class RenderableNote implements RenderableNoteContract, Wireable
{
    protected string|int $id;

    protected ?string $authorName;

    protected string $body;

    protected ?string $authorAvatar;

    protected DateTime|CarbonInterface $createdAt;

    protected DateTime|CarbonInterface $updatedAt;

    protected DateTime|CarbonInterface $deletedAt;

    protected bool $isNote;

    protected ?string $parsedBody;

    protected ?string $label;

    public function __construct(
        string|int      $id,
        ?string         $authorName,
        string          $body,
        ?string         $authorAvatar = null,
        DateTime|Carbon $createdAt = new Carbon(),
        DateTime|Carbon $updatedAt = new Carbon(),
        DateTime|Carbon $deletedAt = new Carbon(),
        bool            $isNote = false,
        ?string         $parsedBody = null,
        ?string         $label = null,
    )
    {
        $this->id = $id;
        $this->authorName = $authorName;
        $this->body = $body;
        $this->authorAvatar = $authorAvatar;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->isNote = $isNote;
        $this->parsedBody = $parsedBody;
        $this->label = $label;
    }

    public function isNote(): bool
    {
        return $this->isNote;
    }

    public function getId(): string|int|null
    {
        return $this->id;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getAuthorAvatar(): ?string
    {
        return $this->authorAvatar;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getParsedBody(): string
    {
        return $this->parsedBody ?? $this->body;
    }

    public function getCreatedAt(): DateTime|CarbonInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime|CarbonInterface
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): DateTime|CarbonInterface
    {
        return $this->deletedAt;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getContentHash(): string
    {
        return "note-$this->id";
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'authorName' => $this->authorName,
            'body' => $this->body,
            'authorAvatar' => $this->authorAvatar,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deletedAt->format('Y-m-d H:i:s'),
            'isNote' => $this->isNote,
            'parsedBody' => $this->parsedBody,
            'label' => $this->label,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            id: $value['id'],
            authorName: $value['authorName'],
            body: $value['body'],
            authorAvatar: $value['authorAvatar'],
            createdAt: new Carbon($value['createdAt']),
            updatedAt: new Carbon($value['updatedAt']),
            deletedAt: new Carbon($value['deletedAt']),
            isNote: $value['isNote'],
            parsedBody: $value['parsedBody'],
            label: $value['label'],
        );
    }
}
