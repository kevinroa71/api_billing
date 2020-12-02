<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201033748 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pay (id INT AUTO_INCREMENT NOT NULL, billing_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_FE8F223C3B025C87 (billing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pay ADD CONSTRAINT FK_FE8F223C3B025C87 FOREIGN KEY (billing_id) REFERENCES billing (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pay');
    }
}
