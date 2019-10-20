<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191020113022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, word INT NOT NULL, url VARCHAR(255) NOT NULL, target LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sentence ADD used INT NOT NULL, ADD count INT NOT NULL, CHANGE length length INT DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE proxy CHANGE port port INT DEFAULT NULL, CHANGE secure secure TINYINT(1) DEFAULT NULL, CHANGE login login VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE down down TINYINT(1) DEFAULT NULL, CHANGE blacklisted blacklisted TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE publication');
        $this->addSql('ALTER TABLE proxy CHANGE port port INT DEFAULT NULL, CHANGE secure secure TINYINT(1) DEFAULT \'NULL\', CHANGE login login VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password password VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE down down TINYINT(1) DEFAULT \'NULL\', CHANGE blacklisted blacklisted TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sentence DROP used, DROP count, CHANGE length length INT DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
