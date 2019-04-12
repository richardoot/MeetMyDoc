<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190410223243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ressource_dossier_patient ADD medecin_id INT NOT NULL');
        $this->addSql('ALTER TABLE ressource_dossier_patient ADD CONSTRAINT FK_3C9A2ECF4F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id)');
        $this->addSql('CREATE INDEX IDX_3C9A2ECF4F31A84 ON ressource_dossier_patient (medecin_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ressource_dossier_patient DROP FOREIGN KEY FK_3C9A2ECF4F31A84');
        $this->addSql('DROP INDEX IDX_3C9A2ECF4F31A84 ON ressource_dossier_patient');
        $this->addSql('ALTER TABLE ressource_dossier_patient DROP medecin_id');
    }
}
