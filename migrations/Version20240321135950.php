<?php

declare(strict_types=1);


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321135950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crate table for scheduler.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE process_schedule (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, process VARCHAR(255) NOT NULL, cron_expression VARCHAR(255) NOT NULL, context CLOB NOT NULL --(DC2Type:json)
        )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE process_schedule');
    }
}
