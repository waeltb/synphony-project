<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421015749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE postulayion');
        $this->addSql('ALTER TABLE postulation ADD offre_id INT NOT NULL');
        $this->addSql('ALTER TABLE postulation ADD CONSTRAINT FK_DA7D4E9B4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('CREATE INDEX IDX_DA7D4E9B4CC8505A ON postulation (offre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE postulayion (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, INDEX IDX_281796464CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE postulayion ADD CONSTRAINT FK_281796464CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE postulation DROP FOREIGN KEY FK_DA7D4E9B4CC8505A');
        $this->addSql('DROP INDEX IDX_DA7D4E9B4CC8505A ON postulation');
        $this->addSql('ALTER TABLE postulation DROP offre_id');
    }
}
