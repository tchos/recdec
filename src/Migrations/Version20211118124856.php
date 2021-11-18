<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118124856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE centre_etat_civil ADD arrondissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE centre_etat_civil ADD CONSTRAINT FK_2CDDD8E2407DBC11 FOREIGN KEY (arrondissement_id) REFERENCES arrondissements (id)');
        $this->addSql('CREATE INDEX IDX_2CDDD8E2407DBC11 ON centre_etat_civil (arrondissement_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE centre_etat_civil DROP FOREIGN KEY FK_2CDDD8E2407DBC11');
        $this->addSql('DROP INDEX IDX_2CDDD8E2407DBC11 ON centre_etat_civil');
        $this->addSql('ALTER TABLE centre_etat_civil DROP arrondissement_id');
    }
}
