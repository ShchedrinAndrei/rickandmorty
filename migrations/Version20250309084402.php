<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250309084402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE episode_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feedback_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE episode (id INT NOT NULL, external_id INT NOT NULL UNIQUE, name VARCHAR(255) NOT NULL, air_date VARCHAR(255) NOT NULL, episode VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unique_external_id ON episode (external_id)');
        $this->addSql('CREATE TABLE feedback (id INT NOT NULL, episode_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, text TEXT NOT NULL, author_name VARCHAR(255) NOT NULL, sentiment_score DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D2294458362B62A0 ON feedback (episode_id)');
        $this->addSql('COMMENT ON COLUMN feedback.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE episode_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feedback_id_seq CASCADE');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D2294458362B62A0');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE feedback');
    }
}
