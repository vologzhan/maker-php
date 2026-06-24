<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260624155830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP CONSTRAINT fk_2fb3d0ee2c94069f');
        $this->addSql('DROP INDEX uniq_2fb3d0ee2c94069f');
        $this->addSql('ALTER TABLE project RENAME COLUMN directory_id TO root_directory_id');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE5202DD20 FOREIGN KEY (root_directory_id) REFERENCES directory (id) NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE5202DD20 ON project (root_directory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE5202DD20');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE5202DD20');
        $this->addSql('ALTER TABLE project RENAME COLUMN root_directory_id TO directory_id');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT fk_2fb3d0ee2c94069f FOREIGN KEY (directory_id) REFERENCES directory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2fb3d0ee2c94069f ON project (directory_id)');
    }
}
