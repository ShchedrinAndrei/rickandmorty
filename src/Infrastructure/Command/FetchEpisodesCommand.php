<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Application\Handler\EpisodeHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:fetch_episodes')]
class FetchEpisodesCommand extends Command
{
    public function __construct(private readonly EpisodeHandler $handler)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->handler->fetchEpisodes();
        } catch (\Exception $exception) {
            $output->writeln('Something went wrong - '.$exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
