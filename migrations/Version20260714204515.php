<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260714204515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE directory DROP CONSTRAINT fk_467844da166d1f9c');
        $this->addSql('DROP INDEX idx_467844da166d1f9c');
        $this->addSql('ALTER TABLE directory DROP project_id');
        $this->addSql('ALTER TABLE project ADD dir_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEEEB38DE6 FOREIGN KEY (dir_id) REFERENCES directory (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EEEEB38DE6 ON project (dir_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE directory ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE directory ADD CONSTRAINT fk_467844da166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_467844da166d1f9c ON directory (project_id)');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEEEB38DE6');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EEEEB38DE6');
        $this->addSql('ALTER TABLE project DROP dir_id');
    }
}
