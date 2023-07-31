<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731143053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DFDD1777A');
        $this->addSql('DROP INDEX IDX_292FFF1DFDD1777A ON vehicule');
        $this->addSql('ALTER TABLE vehicule CHANGE category_vehicule_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_292FFF1D12469DE2 ON vehicule (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D12469DE2');
        $this->addSql('DROP INDEX IDX_292FFF1D12469DE2 ON vehicule');
        $this->addSql('ALTER TABLE vehicule CHANGE category_id category_vehicule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DFDD1777A FOREIGN KEY (category_vehicule_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_292FFF1DFDD1777A ON vehicule (category_vehicule_id)');
    }
}
