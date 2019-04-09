<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409004319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE allergie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier_patient (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, groupe_sanguin_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_58803ED36B899279 (patient_id), INDEX IDX_58803ED3B452768 (groupe_sanguin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier_patient_allergie (dossier_patient_id INT NOT NULL, allergie_id INT NOT NULL, INDEX IDX_E51E87F145F3D65C (dossier_patient_id), INDEX IDX_E51E87F17C86304A (allergie_id), PRIMARY KEY(dossier_patient_id, allergie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier_patient_maladie_grave (dossier_patient_id INT NOT NULL, maladie_grave_id INT NOT NULL, INDEX IDX_B42ADF4245F3D65C (dossier_patient_id), INDEX IDX_B42ADF4249CB0325 (maladie_grave_id), PRIMARY KEY(dossier_patient_id, maladie_grave_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier_patient_vaccin (dossier_patient_id INT NOT NULL, vaccin_id INT NOT NULL, INDEX IDX_57EBAFFE45F3D65C (dossier_patient_id), INDEX IDX_57EBAFFE9B14AC76 (vaccin_id), PRIMARY KEY(dossier_patient_id, vaccin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_sanguin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maladie_grave (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vaccin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossier_patient ADD CONSTRAINT FK_58803ED36B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE dossier_patient ADD CONSTRAINT FK_58803ED3B452768 FOREIGN KEY (groupe_sanguin_id) REFERENCES groupe_sanguin (id)');
        $this->addSql('ALTER TABLE dossier_patient_allergie ADD CONSTRAINT FK_E51E87F145F3D65C FOREIGN KEY (dossier_patient_id) REFERENCES dossier_patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dossier_patient_allergie ADD CONSTRAINT FK_E51E87F17C86304A FOREIGN KEY (allergie_id) REFERENCES allergie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dossier_patient_maladie_grave ADD CONSTRAINT FK_B42ADF4245F3D65C FOREIGN KEY (dossier_patient_id) REFERENCES dossier_patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dossier_patient_maladie_grave ADD CONSTRAINT FK_B42ADF4249CB0325 FOREIGN KEY (maladie_grave_id) REFERENCES maladie_grave (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dossier_patient_vaccin ADD CONSTRAINT FK_57EBAFFE45F3D65C FOREIGN KEY (dossier_patient_id) REFERENCES dossier_patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dossier_patient_vaccin ADD CONSTRAINT FK_57EBAFFE9B14AC76 FOREIGN KEY (vaccin_id) REFERENCES vaccin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medecin ADD CONSTRAINT FK_1BDA53C62195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_1BDA53C62195E0F0 ON medecin (specialite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dossier_patient_allergie DROP FOREIGN KEY FK_E51E87F17C86304A');
        $this->addSql('ALTER TABLE dossier_patient_allergie DROP FOREIGN KEY FK_E51E87F145F3D65C');
        $this->addSql('ALTER TABLE dossier_patient_maladie_grave DROP FOREIGN KEY FK_B42ADF4245F3D65C');
        $this->addSql('ALTER TABLE dossier_patient_vaccin DROP FOREIGN KEY FK_57EBAFFE45F3D65C');
        $this->addSql('ALTER TABLE dossier_patient DROP FOREIGN KEY FK_58803ED3B452768');
        $this->addSql('ALTER TABLE dossier_patient_maladie_grave DROP FOREIGN KEY FK_B42ADF4249CB0325');
        $this->addSql('ALTER TABLE dossier_patient_vaccin DROP FOREIGN KEY FK_57EBAFFE9B14AC76');
        $this->addSql('DROP TABLE allergie');
        $this->addSql('DROP TABLE dossier_patient');
        $this->addSql('DROP TABLE dossier_patient_allergie');
        $this->addSql('DROP TABLE dossier_patient_maladie_grave');
        $this->addSql('DROP TABLE dossier_patient_vaccin');
        $this->addSql('DROP TABLE groupe_sanguin');
        $this->addSql('DROP TABLE maladie_grave');
        $this->addSql('DROP TABLE vaccin');
        $this->addSql('ALTER TABLE medecin DROP FOREIGN KEY FK_1BDA53C62195E0F0');
        $this->addSql('DROP INDEX IDX_1BDA53C62195E0F0 ON medecin');
    }
}
