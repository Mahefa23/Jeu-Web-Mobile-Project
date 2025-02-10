<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206074232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, recette_text_id INT NOT NULL, UNIQUE INDEX UNIQ_49BB6390ADF5EF32 (recette_text_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390ADF5EF32 FOREIGN KEY (recette_text_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE plat DROP recette');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390ADF5EF32');
        $this->addSql('DROP TABLE recette');
        $this->addSql('ALTER TABLE plat ADD recette LONGTEXT NOT NULL');
    }
}
