<?php

namespace Willis1776\Notations;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Closure;
use DateTime;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Willis1776\Notations\Actions\HtmlToMarkdown;
use Willis1776\Notations\Actions\ParseNote;
use Willis1776\Notations\Actions\ToggleNoteReaction;
use Willis1776\Notations\Contracts\Notable;
use Willis1776\Notations\Contracts\Scribe;
use Willis1776\Notations\Contracts\RenderableNote;
use Willis1776\Notations\Database\Factories\NoteFactory;

/**
 * @property int $id
 * @property string $body
 * @property string $body_markdown
 * @property string $body_parsed
 * @property int $author_id
 * @property Model|Scribe $author
 * @property Notable $notable
 * @property-read DateTime|Carbon $created_at
 * @property-read DateTime|Carbon $updated_at
 * @property-read DateTime|Carbon $deleted_at
 */
class Note extends Model implements RenderableNote
{
    use HasFactory;

    protected $fillable = [
        'body',
        'author_type',
        'author_id',
    ];

    public function getTable()
    {
        return Config::getNoteTable();
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function notable(): MorphTo
    {
        return $this->morphTo();
    }

    public function bodyParsed(): Attribute
    {
        return Attribute::make(
            get: fn () => ParseNote::run($this->body),
        );
    }

    public function bodyMarkdown(): Attribute
    {
        return Attribute::make(
            get: fn () => HtmlToMarkdown::run($this->body),
        );
    }

    public function getBodyMarkdown(?Closure $mentionedCallback = null): string
    {
        return HtmlToMarkdown::run(
            html: $this->body,
            mentionedCallback: $mentionedCallback,
        );
    }

    public function isAuthor(Scribe $author)
    {
        return $this->author_id === $author->getKey();
    }

    /**
     * Get the IDs of users mentioned in the note body.
     *
     * @return Collection<Scribe>
     */
    public function getMentioned(): Collection
    {
        $scribeModel = Config::getScribeModel();

        preg_match_all(
            '/<span[^>]*data-type="mention"[^>]*data-id="(\d+)"[^>]*>/',
            $this->body,
            $matches
        );

        return collect($matches[1] ?? [])
            ->map(fn ($userId) => $scribeModel::find($userId))
            ->filter(fn ($mentioned) => $mentioned !== null);
    }

    public function isNote(): bool
    {
        return true;
    }

    public function getId(): string|int|null
    {
        return $this->id;
    }

    public function getAuthorName(): string
    {
        return $this->author?->name;
    }

    public function getAuthorAvatar(): string
    {
        $avatar = null;

        if ($this->author instanceof HasAvatar) {
            $avatar = $this->author?->getFilamentAvatarUrl();
        }

        if (! is_null($avatar)) {
            return $avatar;
        }

        $name = str(Manager::getName($this->author))
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=FFFFFF&background=71717b';
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getParsedBody(): string
    {
        return $this->body_parsed;
    }

    public function getCreatedAt(): DateTime|CarbonInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTime|CarbonInterface
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): DateTime|CarbonInterface
    {
        return $this->deleted_at;
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(NoteReaction::class);
    }

    public function toggleReaction(string $reaction): void
    {
        ToggleNoteReaction::run($this, $reaction, Config::resolveAuthenticatedUser());
    }

    public function getLabel(): ?string
    {
        return null;
    }

    public function getContentHash(): string
    {
        return md5(json_encode([
            'body' => $this->body,
            'reactions' => $this->reactions->pluck('id'),
        ]));
    }

    protected static function newFactory()
    {
        return NoteFactory::new();
    }
}
