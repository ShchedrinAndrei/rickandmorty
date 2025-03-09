<?php

declare(strict_types=1);

namespace App\Domain\Client;

interface SentimentAnalyzeClientInterface
{
    public function analyze(string $text): float;
}
