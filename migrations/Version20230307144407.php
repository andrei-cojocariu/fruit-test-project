<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307144407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fruit_nutritions DROP FOREIGN KEY FK_3198FC6B853A268');
        $this->addSql('DROP INDEX UNIQ_3198FC6B853A268 ON fruit_nutritions');
        $this->addSql('ALTER TABLE fruit_nutritions CHANGE fruit_id_id fruit_id INT NOT NULL');
        $this->addSql('ALTER TABLE fruit_nutritions ADD CONSTRAINT FK_3198FC6BBAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruits (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3198FC6BBAC115F0 ON fruit_nutritions (fruit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fruit_nutritions DROP FOREIGN KEY FK_3198FC6BBAC115F0');
        $this->addSql('DROP INDEX UNIQ_3198FC6BBAC115F0 ON fruit_nutritions');
        $this->addSql('ALTER TABLE fruit_nutritions CHANGE fruit_id fruit_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE fruit_nutritions ADD CONSTRAINT FK_3198FC6B853A268 FOREIGN KEY (fruit_id_id) REFERENCES fruits (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3198FC6B853A268 ON fruit_nutritions (fruit_id_id)');
    }
}
