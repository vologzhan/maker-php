<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260617220719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE controller ADD response_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE controller ADD CONSTRAINT FK_4CF2669AFBF32840 FOREIGN KEY (response_id) REFERENCES response (id)');
        $this->addSql('CREATE INDEX IDX_4CF2669AFBF32840 ON controller (response_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE controller DROP CONSTRAINT FK_4CF2669AFBF32840');
        $this->addSql('DROP INDEX IDX_4CF2669AFBF32840');
        $this->addSql('ALTER TABLE controller DROP response_id');
    }
}
