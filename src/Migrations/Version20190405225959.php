<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190405225959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE creneau (id INT AUTO_INCREMENT NOT NULL, medecin_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, etat VARCHAR(50) NOT NULL, duree INT NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, date_rdv DATE NOT NULL, INDEX IDX_F9668B5F4F31A84 (medecin_id), INDEX IDX_F9668B5F6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATETIME NOT NULL, sexe VARCHAR(8) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, complement_adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medecin (id INT NOT NULL, specialite_id INT NOT NULL, id_national VARCHAR(255) NOT NULL, INDEX IDX_1BDA53C62195E0F0 (specialite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medecin_patient (medecin_id INT NOT NULL, patient_id INT NOT NULL, INDEX IDX_64F312D64F31A84 (medecin_id), INDEX IDX_64F312D66B899279 (patient_id), PRIMARY KEY(medecin_id, patient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, nb_rdvannule INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_medecin (patient_id INT NOT NULL, medecin_id INT NOT NULL, INDEX IDX_46B9062D6B899279 (patient_id), INDEX IDX_46B9062D4F31A84 (medecin_id), PRIMARY KEY(patient_id, medecin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F4F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id)');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE medecin ADD CONSTRAINT FK_1BDA53C62195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE medecin ADD CONSTRAINT FK_1BDA53C6BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medecin_patient ADD CONSTRAINT FK_64F312D64F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medecin_patient ADD CONSTRAINT FK_64F312D66B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_medecin ADD CONSTRAINT FK_46B9062D6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_medecin ADD CONSTRAINT FK_46B9062D4F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE medecin DROP FOREIGN KEY FK_1BDA53C6BF396750');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBBF396750');
        $this->addSql('ALTER TABLE creneau DROP FOREIGN KEY FK_F9668B5F4F31A84');
        $this->addSql('ALTER TABLE medecin_patient DROP FOREIGN KEY FK_64F312D64F31A84');
        $this->addSql('ALTER TABLE patient_medecin DROP FOREIGN KEY FK_46B9062D4F31A84');
        $this->addSql('ALTER TABLE creneau DROP FOREIGN KEY FK_F9668B5F6B899279');
        $this->addSql('ALTER TABLE medecin_patient DROP FOREIGN KEY FK_64F312D66B899279');
        $this->addSql('ALTER TABLE patient_medecin DROP FOREIGN KEY FK_46B9062D6B899279');
        $this->addSql('ALTER TABLE medecin DROP FOREIGN KEY FK_1BDA53C62195E0F0');
        $this->addSql('DROP TABLE creneau');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE medecin');
        $this->addSql('DROP TABLE medecin_patient');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_medecin');
        $this->addSql('DROP TABLE specialite');
    }
}
