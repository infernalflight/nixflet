<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217101936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, first_air_date DATE NOT NULL, overview LONGTEXT DEFAULT NULL, poster VARCHAR(255) DEFAULT NULL, tmdb_id INT DEFAULT NULL, date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, serie_id INT NOT NULL, INDEX IDX_F0E45BA9D94388BD (serie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA9D94388BD FOREIGN KEY (serie_id) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE serie CHANGE name name VARCHAR(255) NOT NULL, CHANGE overview overview LONGTEXT DEFAULT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE genres genres VARCHAR(255) NOT NULL, CHANGE backdrop backdrop VARCHAR(255) DEFAULT NULL, CHANGE poster poster VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE serie RENAME INDEX uniq_aa3a93345e27 TO UNIQ_AA3A93345E237E06A4265897');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA9D94388BD');
        $this->addSql('DROP TABLE season');
        $this->addSql('ALTER TABLE serie CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE overview overview LONGTEXT DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE genres genres VARCHAR(255) NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE backdrop backdrop VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE poster poster VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`');
        $this->addSql('ALTER TABLE serie RENAME INDEX uniq_aa3a93345e237e06a4265897 TO UNIQ_AA3A93345E27');
    }
}
