<?php

declare(strict_types=1);

namespace App\Application\Dto;

readonly class EpisodeFeedbackDto
{
    public function __construct(
        private string $authorName,
        private string $text,
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }
}
