<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308201948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fruit_nutritions CHANGE date_updated date_updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE fruits CHANGE date_updated date_updated DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fruit_nutritions CHANGE date_updated date_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE fruits CHANGE date_updated date_updated DATETIME NOT NULL');
    }
}
