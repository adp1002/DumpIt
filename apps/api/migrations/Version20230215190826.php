<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215190826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add stash and filter tables';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform,
            'Migration can only be executed safely on \'postgresql\'.',
        );

        $this->addSql('CREATE SCHEMA IF NOT EXISTS dumpit');

        $this->addSql(
            'CREATE TABLE dumpit.users (
                id UUID PRIMARY KEY,
                username VARCHAR(30) UNIQUE,
                realm VARCHAR NOT NULL,
                token VARCHAR NOT NULL,
                type VARCHAR NOT NULL CHECK (type IN(\'api\', \'poesessid\'))
            )'
        );

        $this->addSql(
            'CREATE TABLE dumpit.mods (
                id VARCHAR PRIMARY KEY,
                text VARCHAR NOT NULL,
                placeholders SMALLINT NOT NULL
            )'
        );

        $this->addSql(
            'CREATE TABLE dumpit.leagues (
                id VARCHAR PRIMARY KEY,
                realm VARCHAR NOT NULL
            )'
        );

        $this->addSql(
            'CREATE TABLE dumpit.tabs (
                id VARCHAR(64) PRIMARY KEY,
                name VARCHAR NOT NULL,
                index SMALLINT NOT NULL,
                league_id VARCHAR NOT NULL,
                last_sync TIMESTAMP,
                user_id UUID REFERENCES dumpit.users (id)
            )'
        );

        $this->addSql(
            'CREATE TABLE dumpit.items (
                id VARCHAR(64) PRIMARY KEY,
                tab_id VARCHAR(64) REFERENCES dumpit.tabs (id),
                name VARCHAR NOT NULL,
                ilvl SMALLINT NOT NULL,
                base_type VARCHAR NOT NULL
            )'
        );

        $this->addSql(
            'CREATE TABLE dumpit.item_mods (
                item_id VARCHAR(64) REFERENCES dumpit.items (id),
                mod_id VARCHAR(30) REFERENCES dumpit.mods (id),
                values JSON NOT NULL,
                PRIMARY KEY (item_id, mod_id)
            )'
        );

        $this->addSql(
            'CREATE TABLE dumpit.filters (
                id UUID PRIMARY KEY,
                name VARCHAR NOT NULL,
                user_id UUID REFERENCES dumpit.users (id)
            )'
        );

        $this->addSql(
            'CREATE TABLE dumpit.filter_mods (
                filter_id UUID REFERENCES dumpit.filters (id),
                mod_id VARCHAR(30) REFERENCES dumpit.mods (id),
                values JSON NOT NULL,
                condition VARCHAR(5) NOT NULL CHECK (condition IN (\'eq\', \'gt\', \'gte\', \'lt\', \'lte\')),
                PRIMARY KEY (filter_id, mod_id, condition)
            )'
        );

        $this->addSql('CREATE INDEX item_tab_index ON dumpit.items (tab_id)');
        $this->addSql('CREATE INDEX item_ilvl_index ON dumpit.items (ilvl)');
        $this->addSql('CREATE INDEX item_base_type_index ON dumpit.items (base_type)');

        $this->addSql('CREATE INDEX item_mods_index ON dumpit.item_mods (item_id, mod_id, value)');

        $this->addSql('CREATE INDEX filter_mods_index ON dumpit.filter_mods (filter_id)');

        $this->addSql('CREATE INDEX mod_text_index ON dumpit.mods (text)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform,
            'Migration can only be executed safely on \'postgresql\'.',
        );

        $this->addSql('DROP TABLE dumpit.leagues');
        $this->addSql('DROP TABLE dumpit.filter_mods');
        $this->addSql('DROP TABLE dumpit.item_mods');
        $this->addSql('DROP TABLE dumpit.mods');
        $this->addSql('DROP TABLE dumpit.items');
        $this->addSql('DROP TABLE dumpit.filters');
        $this->addSql('DROP TABLE dumpit.tabs');
        $this->addSql('DROP TABLE dumpit.users');
    }
}
