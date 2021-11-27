<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211126164339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acte_deces DROP FOREIGN KEY FK_4F50E0F27DA420AC');
        $this->addSql('DROP INDEX IDX_4F50E0F27DA420AC ON acte_deces');
        $this->addSql('ALTER TABLE acte_deces ADD centre_etat_civil VARCHAR(255) NOT NULL, DROP centre_etat_civil_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acte_deces ADD centre_etat_civil_id INT DEFAULT NULL, DROP centre_etat_civil');
        $this->addSql('ALTER TABLE acte_deces ADD CONSTRAINT FK_4F50E0F27DA420AC FOREIGN KEY (centre_etat_civil_id) REFERENCES centre_etat_civil (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4F50E0F27DA420AC ON acte_deces (centre_etat_civil_id)');
    }
}
