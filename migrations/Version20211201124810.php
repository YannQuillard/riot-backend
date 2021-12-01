<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211201124810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ban (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, champion_id INT NOT NULL, team INT NOT NULL, INDEX IDX_62FED0E5E48FD905 (game_id), INDEX IDX_62FED0E5FA7FD7EB (champion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, game_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pick (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, champion_id INT NOT NULL, team INT NOT NULL, INDEX IDX_99CD0F9BE48FD905 (game_id), INDEX IDX_99CD0F9BFA7FD7EB (champion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, summoner_id VARCHAR(255) NOT NULL, puuid VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_champion (player_id INT NOT NULL, champion_id INT NOT NULL, INDEX IDX_43943F5499E6F5DF (player_id), INDEX IDX_43943F54FA7FD7EB (champion_id), PRIMARY KEY(player_id, champion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ban ADD CONSTRAINT FK_62FED0E5E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE ban ADD CONSTRAINT FK_62FED0E5FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id)');
        $this->addSql('ALTER TABLE pick ADD CONSTRAINT FK_99CD0F9BE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE pick ADD CONSTRAINT FK_99CD0F9BFA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id)');
        $this->addSql('ALTER TABLE player_champion ADD CONSTRAINT FK_43943F5499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_champion ADD CONSTRAINT FK_43943F54FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ban DROP FOREIGN KEY FK_62FED0E5E48FD905');
        $this->addSql('ALTER TABLE pick DROP FOREIGN KEY FK_99CD0F9BE48FD905');
        $this->addSql('ALTER TABLE player_champion DROP FOREIGN KEY FK_43943F5499E6F5DF');
        $this->addSql('DROP TABLE ban');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE `match`');
        $this->addSql('DROP TABLE pick');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_champion');
    }
}
