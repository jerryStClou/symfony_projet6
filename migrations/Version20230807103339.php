<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807103339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD start_date VARCHAR(255) NOT NULL, ADD end_date VARCHAR(255) NOT NULL, DROP start_at, DROP end_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP start_date, DROP end_date');
    }
}
