<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408234916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dossier_patient_medecin (dossier_patient_id INT NOT NULL, medecin_id INT NOT NULL, INDEX IDX_98F8D9945F3D65C (dossier_patient_id), INDEX IDX_98F8D994F31A84 (medecin_id), PRIMARY KEY(dossier_patient_id, medecin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossier_patient_medecin ADD CONSTRAINT FK_98F8D9945F3D65C FOREIGN KEY (dossier_patient_id) REFERENCES dossier_patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dossier_patient_medecin ADD CONSTRAINT FK_98F8D994F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE dossier_patient_medecin');
    }
}
