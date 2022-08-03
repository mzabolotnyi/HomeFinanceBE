<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803183358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency_rate (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', rate NUMERIC(10, 2) NOT NULL, size INT NOT NULL, INDEX IDX_555B7C4D38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE currency_rate ADD CONSTRAINT FK_555B7C4D38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE user ADD default_currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ECD792C0 FOREIGN KEY (default_currency_id) REFERENCES currency (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649ECD792C0 ON user (default_currency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE currency_rate');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649ECD792C0');
        $this->addSql('DROP INDEX IDX_8D93D649ECD792C0 ON user');
        $this->addSql('ALTER TABLE user DROP default_currency_id');
    }
}
