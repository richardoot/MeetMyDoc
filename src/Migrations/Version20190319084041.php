<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190319084041 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE medecin (id INT AUTO_INCREMENT NOT NULL, id_national VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, nb_rdvannule INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD patient_id INT DEFAULT NULL, ADD medecin_id INT DEFAULT NULL, ADD telephone VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD complement_adresse VARCHAR(255) DEFAULT NULL, ADD code_postal VARCHAR(255) NOT NULL, ADD ville VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496B899279 ON user (patient_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494F31A84 ON user (medecin_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494F31A84');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496B899279');
        $this->addSql('DROP TABLE medecin');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP INDEX UNIQ_8D93D6496B899279 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6494F31A84 ON user');
        $this->addSql('ALTER TABLE user DROP patient_id, DROP medecin_id, DROP telephone, DROP adresse, DROP complement_adresse, DROP code_postal, DROP ville');
    }
}
