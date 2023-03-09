<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307194932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform,
            'Migration can only be executed safely on \'postgresql\'.',
        );

        $this->addSql('CREATE TABLE dumpit.api_users (user_id UUID PRIMARY KEY, username VARCHAR NOT NULL UNIQUE, token VARCHAR NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform,
            'Migration can only be executed safely on \'postgresql\'.',
        );

        $this->addSql('DROP TABLE dumpit.api_users');
    }
}
