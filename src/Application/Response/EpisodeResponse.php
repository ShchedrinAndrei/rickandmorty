<?php

declare(strict_types=1);

namespace App\Application\Response;

class EpisodeResponse
{
    public function __construct(
        private Episode $result,
    ) {
    }

    public function getResult(): Episode
    {
        return $this->result;
    }
}
