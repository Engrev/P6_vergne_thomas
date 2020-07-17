<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200716203210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_files CHANGE figure_id figure_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE st_files ADD CONSTRAINT FK_6215B0446D69186E FOREIGN KEY (figure_id_id) REFERENCES st_figures (id)');
        $this->addSql('CREATE INDEX IDX_6215B0446D69186E ON st_files (figure_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_files DROP FOREIGN KEY FK_6215B0446D69186E');
        $this->addSql('DROP INDEX IDX_6215B0446D69186E ON st_files');
        $this->addSql('ALTER TABLE st_files CHANGE figure_id_id figure_id INT NOT NULL');
    }
}
