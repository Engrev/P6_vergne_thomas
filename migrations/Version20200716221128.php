<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200716221128 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_figures DROP FOREIGN KEY FK_E920FABA9777D11E');
        $this->addSql('ALTER TABLE st_figures DROP FOREIGN KEY FK_E920FABA9D86650F');
        $this->addSql('DROP INDEX IDX_E920FABA9D86650F ON st_figures');
        $this->addSql('DROP INDEX IDX_E920FABA9777D11E ON st_figures');
        $this->addSql('ALTER TABLE st_figures ADD category_id INT NOT NULL, ADD user_id INT NOT NULL, DROP category_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE st_figures ADD CONSTRAINT FK_E920FABA12469DE2 FOREIGN KEY (category_id) REFERENCES st_categories (id)');
        $this->addSql('ALTER TABLE st_figures ADD CONSTRAINT FK_E920FABAA76ED395 FOREIGN KEY (user_id) REFERENCES st_users (id)');
        $this->addSql('CREATE INDEX IDX_E920FABA12469DE2 ON st_figures (category_id)');
        $this->addSql('CREATE INDEX IDX_E920FABAA76ED395 ON st_figures (user_id)');
        $this->addSql('ALTER TABLE st_files DROP FOREIGN KEY FK_6215B0446D69186E');
        $this->addSql('DROP INDEX IDX_6215B0446D69186E ON st_files');
        $this->addSql('ALTER TABLE st_files CHANGE figure_id_id figure_id INT NOT NULL');
        $this->addSql('ALTER TABLE st_files ADD CONSTRAINT FK_6215B0445C011B5 FOREIGN KEY (figure_id) REFERENCES st_figures (id)');
        $this->addSql('CREATE INDEX IDX_6215B0445C011B5 ON st_files (figure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_figures DROP FOREIGN KEY FK_E920FABA12469DE2');
        $this->addSql('ALTER TABLE st_figures DROP FOREIGN KEY FK_E920FABAA76ED395');
        $this->addSql('DROP INDEX IDX_E920FABA12469DE2 ON st_figures');
        $this->addSql('DROP INDEX IDX_E920FABAA76ED395 ON st_figures');
        $this->addSql('ALTER TABLE st_figures ADD category_id_id INT NOT NULL, ADD user_id_id INT NOT NULL, DROP category_id, DROP user_id');
        $this->addSql('ALTER TABLE st_figures ADD CONSTRAINT FK_E920FABA9777D11E FOREIGN KEY (category_id_id) REFERENCES st_categories (id)');
        $this->addSql('ALTER TABLE st_figures ADD CONSTRAINT FK_E920FABA9D86650F FOREIGN KEY (user_id_id) REFERENCES st_users (id)');
        $this->addSql('CREATE INDEX IDX_E920FABA9D86650F ON st_figures (user_id_id)');
        $this->addSql('CREATE INDEX IDX_E920FABA9777D11E ON st_figures (category_id_id)');
        $this->addSql('ALTER TABLE st_files DROP FOREIGN KEY FK_6215B0445C011B5');
        $this->addSql('DROP INDEX IDX_6215B0445C011B5 ON st_files');
        $this->addSql('ALTER TABLE st_files CHANGE figure_id figure_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE st_files ADD CONSTRAINT FK_6215B0446D69186E FOREIGN KEY (figure_id_id) REFERENCES st_figures (id)');
        $this->addSql('CREATE INDEX IDX_6215B0446D69186E ON st_files (figure_id_id)');
    }
}
