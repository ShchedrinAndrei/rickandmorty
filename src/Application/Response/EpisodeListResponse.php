<?php

declare(strict_types=1);

namespace App\Application\Response;

use App\Domain\Entity\Episode;

readonly class EpisodeListResponse
{
    public function __construct(
        /**
         * @var array<Episode>
         */
        private array $result,
    ) {
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
