<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230401160904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dislike (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dislike_user (dislike_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D48919A7F16E48BC (dislike_id), INDEX IDX_D48919A7A76ED395 (user_id), PRIMARY KEY(dislike_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dislike_comments (dislike_id INT NOT NULL, comments_id INT NOT NULL, INDEX IDX_515762D7F16E48BC (dislike_id), INDEX IDX_515762D763379586 (comments_id), PRIMARY KEY(dislike_id, comments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE like_user (like_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_54E60A37859BFA32 (like_id), INDEX IDX_54E60A37A76ED395 (user_id), PRIMARY KEY(like_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE like_comments (like_id INT NOT NULL, comments_id INT NOT NULL, INDEX IDX_FDAA6A12859BFA32 (like_id), INDEX IDX_FDAA6A1263379586 (comments_id), PRIMARY KEY(like_id, comments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dislike_user ADD CONSTRAINT FK_D48919A7F16E48BC FOREIGN KEY (dislike_id) REFERENCES dislike (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dislike_user ADD CONSTRAINT FK_D48919A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dislike_comments ADD CONSTRAINT FK_515762D7F16E48BC FOREIGN KEY (dislike_id) REFERENCES dislike (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dislike_comments ADD CONSTRAINT FK_515762D763379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_user ADD CONSTRAINT FK_54E60A37859BFA32 FOREIGN KEY (like_id) REFERENCES `like` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_user ADD CONSTRAINT FK_54E60A37A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_comments ADD CONSTRAINT FK_FDAA6A12859BFA32 FOREIGN KEY (like_id) REFERENCES `like` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_comments ADD CONSTRAINT FK_FDAA6A1263379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dislike_user DROP FOREIGN KEY FK_D48919A7F16E48BC');
        $this->addSql('ALTER TABLE dislike_user DROP FOREIGN KEY FK_D48919A7A76ED395');
        $this->addSql('ALTER TABLE dislike_comments DROP FOREIGN KEY FK_515762D7F16E48BC');
        $this->addSql('ALTER TABLE dislike_comments DROP FOREIGN KEY FK_515762D763379586');
        $this->addSql('ALTER TABLE like_user DROP FOREIGN KEY FK_54E60A37859BFA32');
        $this->addSql('ALTER TABLE like_user DROP FOREIGN KEY FK_54E60A37A76ED395');
        $this->addSql('ALTER TABLE like_comments DROP FOREIGN KEY FK_FDAA6A12859BFA32');
        $this->addSql('ALTER TABLE like_comments DROP FOREIGN KEY FK_FDAA6A1263379586');
        $this->addSql('DROP TABLE dislike');
        $this->addSql('DROP TABLE dislike_user');
        $this->addSql('DROP TABLE dislike_comments');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE like_user');
        $this->addSql('DROP TABLE like_comments');
    }
}
