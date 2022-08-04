<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804152603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE import_method (id INT AUTO_INCREMENT NOT NULL, fields JSON NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account ADD import_method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A444498DDB FOREIGN KEY (import_method_id) REFERENCES import_method (id)');
        $this->addSql('CREATE INDEX IDX_7D3656A444498DDB ON account (import_method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A444498DDB');
        $this->addSql('DROP TABLE import_method');
        $this->addSql('DROP INDEX IDX_7D3656A444498DDB ON account');
        $this->addSql('ALTER TABLE account DROP import_method_id');
    }
}
