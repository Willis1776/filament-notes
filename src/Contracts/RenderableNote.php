<?php

namespace Willis1776\Notations\Contracts;

use Carbon\CarbonInterface;
use DateTime;

interface RenderableNote
{
    public function isNote(): bool;

    public function getId(): string|int|null;

    public function getAuthorName(): string;

    public function getAuthorAvatar(): ?string;

    public function getBody(): string;

    public function getParsedBody(): string;

    public function getCreatedAt(): DateTime|CarbonInterface;

    public function getUpdatedAt(): DateTime|CarbonInterface;

    public function getDeletedAt(): DateTime|CarbonInterface;

    public function getLabel(): ?string;

    public function getContentHash(): string;
}
