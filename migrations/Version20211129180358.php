<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129180358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE best_match (id INT AUTO_INCREMENT NOT NULL, date_match DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE best_match_composition (id INT AUTO_INCREMENT NOT NULL, composition_id_id INT NOT NULL, best_match_id_id INT NOT NULL, win TINYINT(1) NOT NULL, INDEX IDX_4E37D78CC2342328 (composition_id_id), INDEX IDX_4E37D78C2BE60D32 (best_match_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champions (id INT AUTO_INCREMENT NOT NULL, champion_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composition_champion (id INT AUTO_INCREMENT NOT NULL, composition_id_id INT NOT NULL, champion_id_id INT NOT NULL, INDEX IDX_1B80F0ADC2342328 (composition_id_id), INDEX IDX_1B80F0ADCD35C5FE (champion_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compositions (id INT AUTO_INCREMENT NOT NULL, wins INT DEFAULT NULL, losses INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_banned_champion (id INT AUTO_INCREMENT NOT NULL, match_id_id INT NOT NULL, champion_id_id INT NOT NULL, INDEX IDX_352BA45DC12EE1F6 (match_id_id), INDEX IDX_352BA45DCD35C5FE (champion_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_player_champion (id INT AUTO_INCREMENT NOT NULL, match_id_id INT NOT NULL, player_id_id INT NOT NULL, champion_id_id INT NOT NULL, INDEX IDX_1B75DA31C12EE1F6 (match_id_id), INDEX IDX_1B75DA31C036E511 (player_id_id), INDEX IDX_1B75DA31CD35C5FE (champion_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, puuid VARCHAR(255) NOT NULL, summoner_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_champion (id INT AUTO_INCREMENT NOT NULL, champion_id_id INT NOT NULL, player_id_id INT NOT NULL, INDEX IDX_43943F54CD35C5FE (champion_id_id), INDEX IDX_43943F54C036E511 (player_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_match (id INT AUTO_INCREMENT NOT NULL, match_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE best_match_composition ADD CONSTRAINT FK_4E37D78CC2342328 FOREIGN KEY (composition_id_id) REFERENCES compositions (id)');
        $this->addSql('ALTER TABLE best_match_composition ADD CONSTRAINT FK_4E37D78C2BE60D32 FOREIGN KEY (best_match_id_id) REFERENCES best_match (id)');
        $this->addSql('ALTER TABLE composition_champion ADD CONSTRAINT FK_1B80F0ADC2342328 FOREIGN KEY (composition_id_id) REFERENCES compositions (id)');
        $this->addSql('ALTER TABLE composition_champion ADD CONSTRAINT FK_1B80F0ADCD35C5FE FOREIGN KEY (champion_id_id) REFERENCES champions (id)');
        $this->addSql('ALTER TABLE match_banned_champion ADD CONSTRAINT FK_352BA45DC12EE1F6 FOREIGN KEY (match_id_id) REFERENCES player_match (id)');
        $this->addSql('ALTER TABLE match_banned_champion ADD CONSTRAINT FK_352BA45DCD35C5FE FOREIGN KEY (champion_id_id) REFERENCES champions (id)');
        $this->addSql('ALTER TABLE match_player_champion ADD CONSTRAINT FK_1B75DA31C12EE1F6 FOREIGN KEY (match_id_id) REFERENCES player_match (id)');
        $this->addSql('ALTER TABLE match_player_champion ADD CONSTRAINT FK_1B75DA31C036E511 FOREIGN KEY (player_id_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE match_player_champion ADD CONSTRAINT FK_1B75DA31CD35C5FE FOREIGN KEY (champion_id_id) REFERENCES champions (id)');
        $this->addSql('ALTER TABLE player_champion ADD CONSTRAINT FK_43943F54CD35C5FE FOREIGN KEY (champion_id_id) REFERENCES champions (id)');
        $this->addSql('ALTER TABLE player_champion ADD CONSTRAINT FK_43943F54C036E511 FOREIGN KEY (player_id_id) REFERENCES player (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE best_match_composition DROP FOREIGN KEY FK_4E37D78C2BE60D32');
        $this->addSql('ALTER TABLE composition_champion DROP FOREIGN KEY FK_1B80F0ADCD35C5FE');
        $this->addSql('ALTER TABLE match_banned_champion DROP FOREIGN KEY FK_352BA45DCD35C5FE');
        $this->addSql('ALTER TABLE match_player_champion DROP FOREIGN KEY FK_1B75DA31CD35C5FE');
        $this->addSql('ALTER TABLE player_champion DROP FOREIGN KEY FK_43943F54CD35C5FE');
        $this->addSql('ALTER TABLE best_match_composition DROP FOREIGN KEY FK_4E37D78CC2342328');
        $this->addSql('ALTER TABLE composition_champion DROP FOREIGN KEY FK_1B80F0ADC2342328');
        $this->addSql('ALTER TABLE match_player_champion DROP FOREIGN KEY FK_1B75DA31C036E511');
        $this->addSql('ALTER TABLE player_champion DROP FOREIGN KEY FK_43943F54C036E511');
        $this->addSql('ALTER TABLE match_banned_champion DROP FOREIGN KEY FK_352BA45DC12EE1F6');
        $this->addSql('ALTER TABLE match_player_champion DROP FOREIGN KEY FK_1B75DA31C12EE1F6');
        $this->addSql('DROP TABLE best_match');
        $this->addSql('DROP TABLE best_match_composition');
        $this->addSql('DROP TABLE champions');
        $this->addSql('DROP TABLE composition_champion');
        $this->addSql('DROP TABLE compositions');
        $this->addSql('DROP TABLE match_banned_champion');
        $this->addSql('DROP TABLE match_player_champion');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_champion');
        $this->addSql('DROP TABLE player_match');
    }
}
