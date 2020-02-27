<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200227110328 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, equipe_id INT NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_8D93D6496D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acte_deces (id INT AUTO_INCREMENT NOT NULL, centre_etat_civil_id INT DEFAULT NULL, agent_saisie_id INT NOT NULL, numero_acte VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, date_deces DATE NOT NULL, lieu_deces VARCHAR(255) NOT NULL, age INT NOT NULL, date_naissance DATE NOT NULL, lieu_naissance VARCHAR(255) NOT NULL, profession VARCHAR(255) NOT NULL, domicile VARCHAR(255) NOT NULL, declarant VARCHAR(255) NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_4F50E0F27DA420AC (centre_etat_civil_id), INDEX IDX_4F50E0F25670B5A9 (agent_saisie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, responsable VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE acte_deces ADD CONSTRAINT FK_4F50E0F27DA420AC FOREIGN KEY (centre_etat_civil_id) REFERENCES centre_etat_civil (id)');
        $this->addSql('ALTER TABLE acte_deces ADD CONSTRAINT FK_4F50E0F25670B5A9 FOREIGN KEY (agent_saisie_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acte_deces DROP FOREIGN KEY FK_4F50E0F25670B5A9');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496D861B89');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE acte_deces');
        $this->addSql('DROP TABLE equipe');
    }
}
