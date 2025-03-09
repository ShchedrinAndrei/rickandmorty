<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Psr\Log\LoggerInterface;

final class MigrationEventListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $this->logger->info('postGenerateSchema event triggered');

        $schema = $args->getSchema();
        if (!$schema->hasNamespace('public')) {
            $schema->createNamespace('public');
        }
    }
}
