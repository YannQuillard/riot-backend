<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211201133002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE champion_lane (champion_id INT NOT NULL, lane_id INT NOT NULL, INDEX IDX_135764F7FA7FD7EB (champion_id), INDEX IDX_135764F7A128F72F (lane_id), PRIMARY KEY(champion_id, lane_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champion_type (champion_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_408ED690FA7FD7EB (champion_id), INDEX IDX_408ED690C54C8C93 (type_id), PRIMARY KEY(champion_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lane (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE champion_lane ADD CONSTRAINT FK_135764F7FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE champion_lane ADD CONSTRAINT FK_135764F7A128F72F FOREIGN KEY (lane_id) REFERENCES lane (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE champion_type ADD CONSTRAINT FK_408ED690FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE champion_type ADD CONSTRAINT FK_408ED690C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE champion_lane DROP FOREIGN KEY FK_135764F7A128F72F');
        $this->addSql('ALTER TABLE champion_type DROP FOREIGN KEY FK_408ED690C54C8C93');
        $this->addSql('DROP TABLE champion_lane');
        $this->addSql('DROP TABLE champion_type');
        $this->addSql('DROP TABLE lane');
        $this->addSql('DROP TABLE type');
    }
}
