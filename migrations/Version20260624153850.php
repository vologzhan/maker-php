<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260624153850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD directory_id INT NOT NULL');
        $this->addSql('ALTER TABLE project DROP path');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE2C94069F FOREIGN KEY (directory_id) REFERENCES directory (id) NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE2C94069F ON project (directory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE2C94069F');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE2C94069F');
        $this->addSql('ALTER TABLE project ADD path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project DROP directory_id');
    }
}
