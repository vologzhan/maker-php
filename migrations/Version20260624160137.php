<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260624160137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE directory ADD is_root BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT fk_2fb3d0ee5202dd20');
        $this->addSql('DROP INDEX uniq_2fb3d0ee5202dd20');
        $this->addSql('ALTER TABLE project DROP root_directory_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE directory DROP is_root');
        $this->addSql('ALTER TABLE project ADD root_directory_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT fk_2fb3d0ee5202dd20 FOREIGN KEY (root_directory_id) REFERENCES directory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2fb3d0ee5202dd20 ON project (root_directory_id)');
    }
}
