<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200304174326 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles TEXT NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_learner ADD CONSTRAINT FK_2C3DD8591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_learner ADD CONSTRAINT FK_2C3DD86209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instructor ADD CONSTRAINT FK_31FC43DD591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517446209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id)');
        $this->addSql('ALTER TABLE learner ADD CONSTRAINT FK_8EF3834A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1136209CB66 FOREIGN KEY (learner_id) REFERENCES learner (id)');
        $this->addSql('ALTER TABLE salary ADD CONSTRAINT FK_9413BB718C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE learner DROP FOREIGN KEY FK_8EF3834A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE course_learner DROP FOREIGN KEY FK_2C3DD8591CC992');
        $this->addSql('ALTER TABLE course_learner DROP FOREIGN KEY FK_2C3DD86209CB66');
        $this->addSql('ALTER TABLE instructor DROP FOREIGN KEY FK_31FC43DD591CC992');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517446209CB66');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1136209CB66');
        $this->addSql('ALTER TABLE salary DROP FOREIGN KEY FK_9413BB718C4FC193');
    }
}
