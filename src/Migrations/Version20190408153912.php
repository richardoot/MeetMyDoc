<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408153912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE groupe_sanguin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossier_patient ADD groupe_sanguin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier_patient ADD CONSTRAINT FK_58803ED3B452768 FOREIGN KEY (groupe_sanguin_id) REFERENCES groupe_sanguin (id)');
        $this->addSql('CREATE INDEX IDX_58803ED3B452768 ON dossier_patient (groupe_sanguin_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dossier_patient DROP FOREIGN KEY FK_58803ED3B452768');
        $this->addSql('DROP TABLE groupe_sanguin');
        $this->addSql('DROP INDEX IDX_58803ED3B452768 ON dossier_patient');
        $this->addSql('ALTER TABLE dossier_patient DROP groupe_sanguin_id');
    }
}
