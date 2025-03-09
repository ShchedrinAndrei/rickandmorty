<?php

declare(strict_types=1);

namespace App\Domain\Dto;

readonly class EpisodePaginationDto
{
    public function __construct(
        /** @var array<EpisodeDto> */
        private array $episodes,
        private ?int $nextPage,
    ) {
    }

    /**
     * @return array<EpisodeDto>
     */
    public function getEpisodes(): array
    {
        return $this->episodes;
    }

    public function getNextPage(): ?int
    {
        return $this->nextPage;
    }
}
