<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Application\Dto\EpisodeFeedbackDto;
use App\Application\Handler\EpisodeHandler;
use App\Application\Response\Episode;
use App\Application\Response\EpisodeListResponse;
use App\Application\Response\EpisodeResponse;
use App\Domain\Client\EpisodeClientInterface;
use App\Domain\Client\SentimentAnalyzeClientInterface;
use App\Domain\Entity\Episode as EpisodeEntity;
use App\Domain\Entity\Feedback;
use App\Domain\Repository\EpisodeRepositoryInterface;
use App\Infrastructure\Client\TextAnalyzer;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EpisodeHandlerTest extends TestCase
{
    private EpisodeClientInterface $clientMock;
    private SentimentAnalyzeClientInterface $analyzerMock;
    private EpisodeRepositoryInterface $episodeRepositoryMock;
    private EntityManagerInterface $entityManagerMock;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(EpisodeClientInterface::class);
        $this->analyzerMock = $this->createMock(SentimentAnalyzeClientInterface::class);
        $this->episodeRepositoryMock = $this->createMock(EpisodeRepositoryInterface::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
    }

    public function testGetAll(): void
    {
        $episodes = [
            new EpisodeEntity(
                1,
                'Pilot',
                'December 2, 2013',
                'S01E01',
                'https://rickandmortyapi.com/api/episode/1',
                '2017-11-10T12:56:33.798Z'
            ),
            new EpisodeEntity(
                2,
                'Lawnmower Dog',
                'December 9, 2013',
                'S01E02',
                'https://rickandmortyapi.com/api/episode/2',
                '2017-11-10T12:56:33.916Z'
            )
        ];
        $this->episodeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($episodes);

        $handler = new EpisodeHandler(
            $this->clientMock,
            $this->analyzerMock,
            $this->episodeRepositoryMock,
            $this->entityManagerMock
        );

        $response = $handler->getAll();

        $this->assertEquals(new EpisodeListResponse($episodes), $response);
    }

    /**
     * @dataProvider dataProviderFindOne
     */
    public function testFindOne(int $id, bool $success): void
    {
        $episode = new EpisodeEntity(
            1,
            'Pilot',
            'December 2, 2013',
            'S01E01',
            'https://rickandmortyapi.com/api/episode/1',
            '2017-11-10T12:56:33.798Z'
        );
        $episode->addFeedback(
            new Feedback(
                'Great episode!',
                'John Doe',
                0.8,
                $episode
            )
        )->addFeedback(
            new Feedback(
                'I did not like it',
                'Jane Doe',
                0.5,
                $episode
            )
        );
        $this->episodeRepositoryMock
            ->expects($this->once())
            ->method('findOneWithFeedbacks')
            ->willReturnCallback(function ($episodeId) use ($episode) {
                return $episodeId === 1 ? $episode : null;
            });

        $handler = new EpisodeHandler(
            $this->clientMock,
            $this->analyzerMock,
            $this->episodeRepositoryMock,
            $this->entityManagerMock
        );

        if (!$success) {
            $this->expectException(NotFoundHttpException::class);
        }

        $response = $handler->findOne($id);

        if ($success) {
            $this->assertEqualsWithDelta(
                new EpisodeResponse(
                    new Episode(
                        $episode->getName(),
                        $episode->getAirDate(),
                        0.65,
                        [
                            ['author' => 'John Doe', 'text' => 'Great episode!', 'date' => new DateTimeImmutable()],
                            ['author' => 'Jane Doe', 'text' => 'I did not like it', 'date' => new DateTimeImmutable()],
                        ]
                    ),
                ),
                $response,
                0.01
            );
        }
    }

    public static function dataProviderFindOne(): array
    {
        return [
            'success' => [
                'id' => 1,
                'success' => true,
            ],
            'not found' => [
                'id' => 2,
                'success' => false,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderReview
     */
    public function testReview(int $id, EpisodeFeedbackDto $dto, bool $success): void
    {
        $episode = new EpisodeEntity(
            1,
            'Pilot',
            'December 2, 2013',
            'S01E01',
            'https://rickandmortyapi.com/api/episode/1',
            '2017-11-10T12:56:33.798Z'
        );
        $this->episodeRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->willReturnCallback(function ($episodeId) use ($episode) {
                return $episodeId === 1 ? $episode : null;
            });

        $this->analyzerMock
            ->expects($success ? $this->once() : $this->never())
            ->method('analyze')
            ->with($dto->getText())
            ->willReturn(0.8);

        $this->entityManagerMock
            ->expects($success ? $this->once() : $this->never())
            ->method('persist')
            ->with($this->callback(function (Feedback $feedback) use ($dto, $episode) {
                return $feedback->getText() === $dto->getText()
                    && $feedback->getAuthorName() === $dto->getAuthorName()
                    && $feedback->getSentimentScore() === 0.8
                    && $feedback->getEpisode() === $episode;
            }));
        $this->entityManagerMock
            ->expects($success ? $this->once() : $this->never())
            ->method('flush');

        $handler = new EpisodeHandler(
            $this->clientMock,
            $this->analyzerMock,
            $this->episodeRepositoryMock,
            $this->entityManagerMock
        );

        if (!$success) {
            $this->expectException(NotFoundHttpException::class);
        }

        $handler->review($id, $dto);
    }

    public static function dataProviderReview(): array
    {
        return [
            'success' => [
                'id' => 1,
                'dto' => new EpisodeFeedbackDto('Great episode!', 'John Doe'),
                'success' => true,
            ],
            'not found' => [
                'id' => 2,
                'dto' => new EpisodeFeedbackDto('Great episode!', 'John Doe'),
                'success' => false,
            ],
        ];
    }
}