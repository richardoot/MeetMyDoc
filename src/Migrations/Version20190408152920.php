<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408152920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dossier_patient ADD patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier_patient ADD CONSTRAINT FK_58803ED36B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_58803ED36B899279 ON dossier_patient (patient_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dossier_patient DROP FOREIGN KEY FK_58803ED36B899279');
        $this->addSql('DROP INDEX UNIQ_58803ED36B899279 ON dossier_patient');
        $this->addSql('ALTER TABLE dossier_patient DROP patient_id');
    }
}
