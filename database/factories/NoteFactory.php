<?php

namespace Willis1776\Notations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Willis1776\Notations\Note;
use Willis1776\Notations\Contracts\Notable;
use Willis1776\Notations\Contracts\Scribe;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->paragraph,
        ];
    }

    public function commentable(Notable $notable): self
    {
        return $this->state(fn (array $attributes) => [
            'notable_type' => $notable->getMorphClass(),
            'notable_id' => $notable->getKey(),
        ]);
    }

    public function author(Scribe $author): self
    {
        return $this->state(fn (array $attributes) => [
            'author_type' => $author->getMorphClass(),
            'author_id' => $author->getKey(),
        ]);
    }
}
