<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Domain\Client\EpisodeClientInterface;
use App\Domain\Dto\EpisodeDto;
use App\Domain\Dto\EpisodePaginationDto;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class EpisodeClient implements EpisodeClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private DenormalizerInterface $denormalizer,
        private string $url,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function getAll(int $page = 1): EpisodePaginationDto
    {
        $result = $this->httpClient->request(
            'GET',
            $this->url.'/episode',
            ['query' => ['page' => $page]]
        );

        $response = $result->toArray();

        return new EpisodePaginationDto(
            array_map(
                fn (array $episode) => $this->denormalizer->denormalize($episode, EpisodeDto::class),
                $response['results']
            ),
            $page === $response['info']['pages'] ? null : $page + 1
        );
    }
}
