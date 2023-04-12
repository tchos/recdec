<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326104719 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE decede (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, sexe VARCHAR(1) NOT NULL, naissance DATE DEFAULT NULL, date_entree_morgue DATE DEFAULT NULL, lieu_deces VARCHAR(255) DEFAULT NULL, date_sortie_morgue DATE DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, lieu_inhumation VARCHAR(255) DEFAULT NULL, cni VARCHAR(32) DEFAULT NULL, telephone_depositaire VARCHAR(24) DEFAULT NULL, ville VARCHAR(32) DEFAULT NULL, region VARCHAR(32) DEFAULT NULL, fosa VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE morgue CHANGE forsa fosa VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE decede');
        $this->addSql('ALTER TABLE morgue CHANGE fosa forsa VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
