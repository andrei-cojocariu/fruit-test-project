<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307143935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fruit_nutritions (id INT AUTO_INCREMENT NOT NULL, fruit_id_id INT NOT NULL, carbohydrates DOUBLE PRECISION NOT NULL, protein DOUBLE PRECISION NOT NULL, fat DOUBLE PRECISION NOT NULL, calories DOUBLE PRECISION NOT NULL, sugar DOUBLE PRECISION NOT NULL, date_updated DATETIME NOT NULL, date_created DATETIME NOT NULL, status INT NOT NULL, UNIQUE INDEX UNIQ_3198FC6B853A268 (fruit_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fruits (id INT AUTO_INCREMENT NOT NULL, fv_id INT NOT NULL, name VARCHAR(255) NOT NULL, family VARCHAR(255) NOT NULL, fruit_order VARCHAR(255) NOT NULL, date_updated DATETIME NOT NULL, date_created DATETIME NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fruit_nutritions ADD CONSTRAINT FK_3198FC6B853A268 FOREIGN KEY (fruit_id_id) REFERENCES fruits (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fruit_nutritions DROP FOREIGN KEY FK_3198FC6B853A268');
        $this->addSql('DROP TABLE fruit_nutritions');
        $this->addSql('DROP TABLE fruits');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
