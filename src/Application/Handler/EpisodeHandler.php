<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Dto\EpisodeFeedbackDto;
use App\Application\Response\Episode;
use App\Application\Response\EpisodeListResponse;
use App\Application\Response\EpisodeResponse;
use App\Domain\Client\EpisodeClientInterface;
use App\Domain\Client\SentimentAnalyzeClientInterface;
use App\Domain\Entity\Episode as EpisodeEntity;
use App\Domain\Entity\Feedback;
use App\Domain\Repository\EpisodeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class EpisodeHandler
{
    public function __construct(
        private EpisodeClientInterface $client,
        private SentimentAnalyzeClientInterface $analyzer,
        private EpisodeRepositoryInterface $episodeRepository,
        private EntityManagerInterface $em,
    ) {
    }

    public function getAll(): EpisodeListResponse
    {
        return new EpisodeListResponse($this->episodeRepository->findAll());
    }

    public function review(int $episodeId, EpisodeFeedbackDto $dto): void
    {
        $episode = $this->episodeRepository->find($episodeId);

        if (null === $episode) {
            throw new NotFoundHttpException(sprintf('Episode %s not found!', $episodeId));
        }

        $score = $this->analyzer->analyze($dto->getText());

        $feedback = new Feedback(
            $dto->getText(),
            $dto->getAuthorName(),
            $score,
            $episode,
        );

        $this->em->persist($feedback);
        $this->em->flush();
    }

    public function findOne(int $episodeId): EpisodeResponse
    {
        $episode = $this->episodeRepository->findOneWithFeedbacks($episodeId);

        if (null === $episode) {
            throw new NotFoundHttpException(sprintf('Episode %s not found!', $episodeId));
        }

        $averageScore = !$episode->getFeedbacks()->isEmpty()
            ? round(
                array_sum(
                    array_map(
                        fn (Feedback $f) => $f->getSentimentScore(), $episode->getFeedbacks()->toArray())
                ) / $episode->getFeedbacks()->count(),
                2
            )
            : null;

        $feedbacks = array_map(
            fn (Feedback $f) => ['author' => $f->getAuthorName(), 'text' => $f->getText(), 'date' => $f->getCreatedAt()],
            $episode->getFeedbacks()->slice(0, 3)
        );

        return new EpisodeResponse(
            new Episode(
                $episode->getName(),
                $episode->getAirDate(),
                $averageScore,
                $feedbacks
            )
        );
    }

    public function fetchEpisodes(): void
    {
        $page = 1;
        $episodes = [];

        do {
            $results = $this->client->getAll($page);
            $episodes = array_merge($episodes, $results->getEpisodes());
            $page = $results->getNextPage();
        } while (null !== $page);

        foreach ($episodes as $episode) {
            $episode = new EpisodeEntity(
                $episode->getId(),
                $episode->getName(),
                $episode->getAirDate(),
                $episode->getEpisode(),
                $episode->getUrl(),
                $episode->getCreated()
            );

            $this->em->persist($episode);
        }

        $this->em->flush();
    }
}
