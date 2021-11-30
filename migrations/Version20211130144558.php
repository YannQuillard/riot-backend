<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211130144558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE best_match (id INT AUTO_INCREMENT NOT NULL, date_match DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE best_match_composition (id INT AUTO_INCREMENT NOT NULL, composition_id INT NOT NULL, best_match_id INT NOT NULL, win TINYINT(1) NOT NULL, INDEX IDX_4E37D78C87A2E12 (composition_id), INDEX IDX_4E37D78CAB3FDE87 (best_match_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE best_match_composition ADD CONSTRAINT FK_4E37D78C87A2E12 FOREIGN KEY (composition_id) REFERENCES composition (id)');
        $this->addSql('ALTER TABLE best_match_composition ADD CONSTRAINT FK_4E37D78CAB3FDE87 FOREIGN KEY (best_match_id) REFERENCES best_match (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE best_match_composition DROP FOREIGN KEY FK_4E37D78CAB3FDE87');
        $this->addSql('DROP TABLE best_match');
        $this->addSql('DROP TABLE best_match_composition');
    }
}
