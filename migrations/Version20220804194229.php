<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804194229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, account_id INT NOT NULL, category_id INT DEFAULT NULL, transfer_to_link_id INT NOT NULL, user_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', comment VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, external_id VARCHAR(255) DEFAULT NULL, INDEX IDX_723705D138248176 (currency_id), INDEX IDX_723705D19B6B5FBA (account_id), INDEX IDX_723705D112469DE2 (category_id), INDEX IDX_723705D1F9F1016D (transfer_to_link_id), INDEX IDX_723705D1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D138248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F9F1016D FOREIGN KEY (transfer_to_link_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F9F1016D');
        $this->addSql('DROP TABLE transaction');
    }
}
