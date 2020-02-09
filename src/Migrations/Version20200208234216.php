<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200208234216 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, duration VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_learner (course_id INT NOT NULL, learner_id INT NOT NULL, INDEX IDX_2C3DD8591CC992 (course_id), INDEX IDX_2C3DD86209CB66 (learner_id), PRIMARY KEY(course_id, learner_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instructor (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, INDEX IDX_31FC43DD591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, learner_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, sent_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_906517446209CB66 (learner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE learner (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, job VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, learner_id INT NOT NULL, status VARCHAR(255) NOT NULL, obtained_at DATETIME NOT NULL, INDEX IDX_136AC1136209CB66 (learner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_learner ADD CONSTRAINT FK_2C3DD8591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_learner ADD CONSTRAINT FK_2C3DD86209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instructor ADD CONSTRAINT FK_31FC43DD591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517446209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1136209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE course_learner DROP FOREIGN KEY FK_2C3DD8591CC992');
        $this->addSql('ALTER TABLE instructor DROP FOREIGN KEY FK_31FC43DD591CC992');
        $this->addSql('ALTER TABLE course_learner DROP FOREIGN KEY FK_2C3DD86209CB66');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517446209CB66');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1136209CB66');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_learner');
        $this->addSql('DROP TABLE instructor');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE learner');
        $this->addSql('DROP TABLE result');
    }
}
