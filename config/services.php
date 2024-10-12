<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Symfony\MigrationEventListener;
use Doctrine\ORM\Tools\ToolEvents;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
//        ->bind(nameOrFqcn: '$enviroment', valueOrRef: env(name: 'APP_ENV'));

    $services->load(
        namespace: 'App\\',
        resource: '../src/*',
    )->exclude(excludes: [
        '../src/Kernel.php',
        '../src/Entity',
    ]);

    $services->set(MigrationEventListener::class)
        ->tag(name: 'doctrine.event_listener', attributes: ['event' => ToolEvents::postGenerateSchema]);
};