<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804552728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $monobankFields = json_encode([["name" => "apiKey", "type" => "string"]]);
        $privatbankFields = json_encode([
            ["name" => "merchantId", "type" => "string"],
            ["name" => "merchantPassword", "type" => "string"],
            ["name" => "cardNumber", "type" => "string"]
        ]);

        $this->addSql("INSERT INTO import_method (name, slug, fields) VALUES 
            ('Monobank', 'monobank', '$monobankFields'), 
            ('Privatbank', 'privatbank', '$privatbankFields')"
        );
    }

    public function down(Schema $schema): void
    {
    }
}
