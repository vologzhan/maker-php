<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260627104053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE controller ADD file_id INT NOT NULL');
        $this->addSql('ALTER TABLE controller DROP name');
        $this->addSql('ALTER TABLE controller DROP filepath');
        $this->addSql('ALTER TABLE controller ADD CONSTRAINT FK_4CF2669A93CB796C FOREIGN KEY (file_id) REFERENCES file (id) NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4CF2669A93CB796C ON controller (file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE controller DROP CONSTRAINT FK_4CF2669A93CB796C');
        $this->addSql('DROP INDEX UNIQ_4CF2669A93CB796C');
        $this->addSql('ALTER TABLE controller ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE controller ADD filepath VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE controller DROP file_id');
    }
}
