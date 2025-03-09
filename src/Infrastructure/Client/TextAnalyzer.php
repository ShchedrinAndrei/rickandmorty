<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Domain\Client\SentimentAnalyzeClientInterface;

readonly class TextAnalyzer implements SentimentAnalyzeClientInterface
{
    public function analyze(string $text): float
    {
        return random_int(0, PHP_INT_MAX) / PHP_INT_MAX;
    }
}
