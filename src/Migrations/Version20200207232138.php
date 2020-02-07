<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200207232138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, learner_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, sent_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_906517446209CB66 (learner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, learner_id INT NOT NULL, status VARCHAR(255) NOT NULL, published_at DATETIME NOT NULL, INDEX IDX_136AC1136209CB66 (learner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary (id INT AUTO_INCREMENT NOT NULL, instructor_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, paid_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9413BB718C4FC193 (instructor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517446209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1136209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id)');
        $this->addSql('ALTER TABLE salary ADD CONSTRAINT FK_9413BB718C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE salary');
    }
}
