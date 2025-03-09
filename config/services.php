<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Client\EpisodeClient;
use App\Infrastructure\Symfony\MigrationEventListener;
use Doctrine\ORM\Tools\ToolEvents;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load(
        namespace: 'App\\',
        resource: '../src/*',
    )->exclude(excludes: [
        '../src/Kernel.php',
        '../src/Application/Dto',
        '../src/Application/Response',
        '../src/Domain/Entity',
        '../src/Domain/Dto',
    ]);

    $services->set(MigrationEventListener::class)
        ->tag(name: 'doctrine.event_listener', attributes: ['event' => ToolEvents::postGenerateSchema]);

    $services->set(EpisodeClient::class)
        ->arg('$url', env('RICK_AND_MORTY_URL'));
};