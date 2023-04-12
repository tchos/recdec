<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326105451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE decede ADD agent_saisie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE decede ADD CONSTRAINT FK_28B37E805670B5A9 FOREIGN KEY (agent_saisie_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_28B37E805670B5A9 ON decede (agent_saisie_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE decede DROP FOREIGN KEY FK_28B37E805670B5A9');
        $this->addSql('DROP INDEX IDX_28B37E805670B5A9 ON decede');
        $this->addSql('ALTER TABLE decede DROP agent_saisie_id');
    }
}
