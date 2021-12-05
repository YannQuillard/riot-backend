<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211205162813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE best_match (id INT AUTO_INCREMENT NOT NULL, date_match DATETIME NOT NULL, riot_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE best_match_composition (id INT AUTO_INCREMENT NOT NULL, composition_id INT NOT NULL, best_match_id INT NOT NULL, win TINYINT(1) NOT NULL, INDEX IDX_4E37D78C87A2E12 (composition_id), INDEX IDX_4E37D78CAB3FDE87 (best_match_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champion (id INT AUTO_INCREMENT NOT NULL, riot_id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, wins INT DEFAULT NULL, losses INT DEFAULT NULL, image_loading VARCHAR(255) NOT NULL, image_splash VARCHAR(255) NOT NULL, win_rate INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champion_lane (champion_id INT NOT NULL, lane_id INT NOT NULL, INDEX IDX_135764F7FA7FD7EB (champion_id), INDEX IDX_135764F7A128F72F (lane_id), PRIMARY KEY(champion_id, lane_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champion_type (champion_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_408ED690FA7FD7EB (champion_id), INDEX IDX_408ED690C54C8C93 (type_id), PRIMARY KEY(champion_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composition (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(255) NOT NULL, wins INT DEFAULT NULL, losses INT DEFAULT NULL, win_rate INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composition_champion (composition_id INT NOT NULL, champion_id INT NOT NULL, INDEX IDX_1B80F0AD87A2E12 (composition_id), INDEX IDX_1B80F0ADFA7FD7EB (champion_id), PRIMARY KEY(composition_id, champion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lane (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, summoner_id VARCHAR(255) NOT NULL, puuid VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_champion (player_id INT NOT NULL, champion_id INT NOT NULL, INDEX IDX_43943F5499E6F5DF (player_id), INDEX IDX_43943F54FA7FD7EB (champion_id), PRIMARY KEY(player_id, champion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE best_match_composition ADD CONSTRAINT FK_4E37D78C87A2E12 FOREIGN KEY (composition_id) REFERENCES composition (id)');
        $this->addSql('ALTER TABLE best_match_composition ADD CONSTRAINT FK_4E37D78CAB3FDE87 FOREIGN KEY (best_match_id) REFERENCES best_match (id)');
        $this->addSql('ALTER TABLE champion_lane ADD CONSTRAINT FK_135764F7FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE champion_lane ADD CONSTRAINT FK_135764F7A128F72F FOREIGN KEY (lane_id) REFERENCES lane (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE champion_type ADD CONSTRAINT FK_408ED690FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE champion_type ADD CONSTRAINT FK_408ED690C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE composition_champion ADD CONSTRAINT FK_1B80F0AD87A2E12 FOREIGN KEY (composition_id) REFERENCES composition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE composition_champion ADD CONSTRAINT FK_1B80F0ADFA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_champion ADD CONSTRAINT FK_43943F5499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_champion ADD CONSTRAINT FK_43943F54FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE best_match_composition DROP FOREIGN KEY FK_4E37D78CAB3FDE87');
        $this->addSql('ALTER TABLE champion_lane DROP FOREIGN KEY FK_135764F7FA7FD7EB');
        $this->addSql('ALTER TABLE champion_type DROP FOREIGN KEY FK_408ED690FA7FD7EB');
        $this->addSql('ALTER TABLE composition_champion DROP FOREIGN KEY FK_1B80F0ADFA7FD7EB');
        $this->addSql('ALTER TABLE player_champion DROP FOREIGN KEY FK_43943F54FA7FD7EB');
        $this->addSql('ALTER TABLE best_match_composition DROP FOREIGN KEY FK_4E37D78C87A2E12');
        $this->addSql('ALTER TABLE composition_champion DROP FOREIGN KEY FK_1B80F0AD87A2E12');
        $this->addSql('ALTER TABLE champion_lane DROP FOREIGN KEY FK_135764F7A128F72F');
        $this->addSql('ALTER TABLE player_champion DROP FOREIGN KEY FK_43943F5499E6F5DF');
        $this->addSql('ALTER TABLE champion_type DROP FOREIGN KEY FK_408ED690C54C8C93');
        $this->addSql('DROP TABLE best_match');
        $this->addSql('DROP TABLE best_match_composition');
        $this->addSql('DROP TABLE champion');
        $this->addSql('DROP TABLE champion_lane');
        $this->addSql('DROP TABLE champion_type');
        $this->addSql('DROP TABLE composition');
        $this->addSql('DROP TABLE composition_champion');
        $this->addSql('DROP TABLE lane');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_champion');
        $this->addSql('DROP TABLE type');
    }
}
