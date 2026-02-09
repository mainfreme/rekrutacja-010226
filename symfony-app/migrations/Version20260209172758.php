<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209172758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Dodaje kolumne token';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users add token TEXT default NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users drop column token');
    }
}
