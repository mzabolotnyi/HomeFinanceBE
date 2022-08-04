<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804195104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F9F1016D');
        $this->addSql('DROP INDEX IDX_723705D1F9F1016D ON transaction');
        $this->addSql('ALTER TABLE transaction ADD transfer_to_id INT DEFAULT NULL, DROP transfer_to_link_id');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D193B1BF3D FOREIGN KEY (transfer_to_id) REFERENCES transaction (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D193B1BF3D ON transaction (transfer_to_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D193B1BF3D');
        $this->addSql('DROP INDEX UNIQ_723705D193B1BF3D ON transaction');
        $this->addSql('ALTER TABLE transaction ADD transfer_to_link_id INT NOT NULL, DROP transfer_to_id');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F9F1016D FOREIGN KEY (transfer_to_link_id) REFERENCES transaction (id)');
        $this->addSql('CREATE INDEX IDX_723705D1F9F1016D ON transaction (transfer_to_link_id)');
    }
}
