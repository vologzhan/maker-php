<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260715150540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response ADD file_id INT NOT NULL');
        $this->addSql('ALTER TABLE response DROP name');
        $this->addSql('ALTER TABLE response DROP filepath');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB93CB796C FOREIGN KEY (file_id) REFERENCES file (id) NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3E7B0BFB93CB796C ON response (file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response DROP CONSTRAINT FK_3E7B0BFB93CB796C');
        $this->addSql('DROP INDEX UNIQ_3E7B0BFB93CB796C');
        $this->addSql('ALTER TABLE response ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE response ADD filepath VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE response DROP file_id');
    }
}
