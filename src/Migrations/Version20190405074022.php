<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190405074022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE medecin_patient (medecin_id INT NOT NULL, patient_id INT NOT NULL, INDEX IDX_64F312D64F31A84 (medecin_id), INDEX IDX_64F312D66B899279 (patient_id), PRIMARY KEY(medecin_id, patient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_medecin (patient_id INT NOT NULL, medecin_id INT NOT NULL, INDEX IDX_46B9062D6B899279 (patient_id), INDEX IDX_46B9062D4F31A84 (medecin_id), PRIMARY KEY(patient_id, medecin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medecin_patient ADD CONSTRAINT FK_64F312D64F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medecin_patient ADD CONSTRAINT FK_64F312D66B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_medecin ADD CONSTRAINT FK_46B9062D6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_medecin ADD CONSTRAINT FK_46B9062D4F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creneau CHANGE medecin_id medecin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medecin ADD specialite_id INT NOT NULL');
        $this->addSql('ALTER TABLE medecin ADD CONSTRAINT FK_1BDA53C62195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_1BDA53C62195E0F0 ON medecin (specialite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE medecin DROP FOREIGN KEY FK_1BDA53C62195E0F0');
        $this->addSql('DROP TABLE medecin_patient');
        $this->addSql('DROP TABLE patient_medecin');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('ALTER TABLE creneau CHANGE medecin_id medecin_id INT NOT NULL');
        $this->addSql('DROP INDEX IDX_1BDA53C62195E0F0 ON medecin');
        $this->addSql('ALTER TABLE medecin DROP specialite_id');
    }
}
