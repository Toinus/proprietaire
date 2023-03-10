<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215170509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chaton (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(50) NOT NULL, sterilise TINYINT(1) NOT NULL, photo VARCHAR(255) NOT NULL, INDEX IDX_EED8B06BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaire_chaton (proprietaire_id INT NOT NULL, chaton_id INT NOT NULL, INDEX IDX_2CFE18F776C50E4A (proprietaire_id), INDEX IDX_2CFE18F7640066C9 (chaton_id), PRIMARY KEY(proprietaire_id, chaton_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chaton ADD CONSTRAINT FK_EED8B06BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE proprietaire_chaton ADD CONSTRAINT FK_2CFE18F776C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proprietaire_chaton ADD CONSTRAINT FK_2CFE18F7640066C9 FOREIGN KEY (chaton_id) REFERENCES chaton (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chaton DROP FOREIGN KEY FK_EED8B06BCF5E72D');
        $this->addSql('ALTER TABLE proprietaire_chaton DROP FOREIGN KEY FK_2CFE18F776C50E4A');
        $this->addSql('ALTER TABLE proprietaire_chaton DROP FOREIGN KEY FK_2CFE18F7640066C9');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE chaton');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('DROP TABLE proprietaire_chaton');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
