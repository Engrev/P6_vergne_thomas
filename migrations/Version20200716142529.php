<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200716142529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE st_messages');
        $this->addSql('ALTER TABLE st_files CHANGE id_figure id_figure_id INT NOT NULL');
        $this->addSql('ALTER TABLE st_files ADD CONSTRAINT FK_6215B04485F5AD92 FOREIGN KEY (id_figure_id) REFERENCES st_figures (id)');
        $this->addSql('CREATE INDEX IDX_6215B04485F5AD92 ON st_files (id_figure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE st_messages (id INT AUTO_INCREMENT NOT NULL, id_figure INT NOT NULL, id_user INT NOT NULL, message LONGBLOB NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE st_files DROP FOREIGN KEY FK_6215B04485F5AD92');
        $this->addSql('DROP INDEX IDX_6215B04485F5AD92 ON st_files');
        $this->addSql('ALTER TABLE st_files CHANGE id_figure_id id_figure INT NOT NULL');
    }
}
