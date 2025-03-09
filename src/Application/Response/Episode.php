<?php

declare(strict_types=1);

namespace App\Application\Response;

readonly class Episode
{
    public function __construct(
        private string $name,
        private string $releaseDate,
        private ?float $avgSentimentScore,
        private array $reviews,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function getAvgSentimentScore(): ?float
    {
        return $this->avgSentimentScore;
    }

    public function getReviews(): array
    {
        return $this->reviews;
    }
}
