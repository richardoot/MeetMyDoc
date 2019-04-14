<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190414173256 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ressource_dossier_patient (id INT AUTO_INCREMENT NOT NULL, type_ressource_dossier_patient_id INT NOT NULL, dossier_patient_id INT NOT NULL, medecin_id INT NOT NULL, url_ressource VARCHAR(255) NOT NULL, INDEX IDX_3C9A2ECF98D86708 (type_ressource_dossier_patient_id), INDEX IDX_3C9A2ECF45F3D65C (dossier_patient_id), INDEX IDX_3C9A2ECF4F31A84 (medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_ressource_dossier_patient (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ressource_dossier_patient ADD CONSTRAINT FK_3C9A2ECF98D86708 FOREIGN KEY (type_ressource_dossier_patient_id) REFERENCES type_ressource_dossier_patient (id)');
        $this->addSql('ALTER TABLE ressource_dossier_patient ADD CONSTRAINT FK_3C9A2ECF45F3D65C FOREIGN KEY (dossier_patient_id) REFERENCES dossier_patient (id)');
        $this->addSql('ALTER TABLE ressource_dossier_patient ADD CONSTRAINT FK_3C9A2ECF4F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id)');
        $this->addSql('ALTER TABLE creneau ADD motif LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ressource_dossier_patient DROP FOREIGN KEY FK_3C9A2ECF98D86708');
        $this->addSql('DROP TABLE ressource_dossier_patient');
        $this->addSql('DROP TABLE type_ressource_dossier_patient');
        $this->addSql('ALTER TABLE creneau DROP motif');
    }
}
