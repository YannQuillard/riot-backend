<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211130105903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE champion (id INT AUTO_INCREMENT NOT NULL, riot_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composition (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(255) NOT NULL, wins INT DEFAULT NULL, losses INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composition_champion (composition_id INT NOT NULL, champion_id INT NOT NULL, INDEX IDX_1B80F0AD87A2E12 (composition_id), INDEX IDX_1B80F0ADFA7FD7EB (champion_id), PRIMARY KEY(composition_id, champion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE composition_champion ADD CONSTRAINT FK_1B80F0AD87A2E12 FOREIGN KEY (composition_id) REFERENCES composition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE composition_champion ADD CONSTRAINT FK_1B80F0ADFA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE composition_champion DROP FOREIGN KEY FK_1B80F0ADFA7FD7EB');
        $this->addSql('ALTER TABLE composition_champion DROP FOREIGN KEY FK_1B80F0AD87A2E12');
        $this->addSql('DROP TABLE champion');
        $this->addSql('DROP TABLE composition');
        $this->addSql('DROP TABLE composition_champion');
    }
}
