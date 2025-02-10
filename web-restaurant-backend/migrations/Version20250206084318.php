<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206084318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plat_ingredient (plat_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_E0ED47FBD73DB560 (plat_id), INDEX IDX_E0ED47FB933FE08C (ingredient_id), PRIMARY KEY(plat_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plat_ingredient ADD CONSTRAINT FK_E0ED47FBD73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat_ingredient ADD CONSTRAINT FK_E0ED47FB933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plat_ingredient DROP FOREIGN KEY FK_E0ED47FBD73DB560');
        $this->addSql('ALTER TABLE plat_ingredient DROP FOREIGN KEY FK_E0ED47FB933FE08C');
        $this->addSql('DROP TABLE plat_ingredient');
    }
}
