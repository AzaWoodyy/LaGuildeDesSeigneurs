<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306125705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_937AB03499E6F5DF ON character (player_id)');
        $this->addSql('ALTER TABLE player ALTER firstname TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE player ALTER lastname TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE player ALTER identifier TYPE VARCHAR(40)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03499E6F5DF');
        $this->addSql('DROP INDEX IDX_937AB03499E6F5DF');
        $this->addSql('ALTER TABLE character DROP player_id');
        $this->addSql('ALTER TABLE player ALTER firstname TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE player ALTER lastname TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE player ALTER identifier TYPE VARCHAR(255)');
    }
}
