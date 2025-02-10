<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206075249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390ADF5EF32');
        $this->addSql('DROP INDEX UNIQ_49BB6390ADF5EF32 ON recette');
        $this->addSql('ALTER TABLE recette CHANGE recette_text recette_text LONGTEXT NOT NULL, CHANGE recette_text_id plat_id INT NOT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id)');
        $this->addSql('CREATE INDEX IDX_49BB6390D73DB560 ON recette (plat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390D73DB560');
        $this->addSql('DROP INDEX IDX_49BB6390D73DB560 ON recette');
        $this->addSql('ALTER TABLE recette CHANGE recette_text recette_text TEXT NOT NULL, CHANGE plat_id recette_text_id INT NOT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390ADF5EF32 FOREIGN KEY (recette_text_id) REFERENCES plat (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_49BB6390ADF5EF32 ON recette (recette_text_id)');
    }
}
