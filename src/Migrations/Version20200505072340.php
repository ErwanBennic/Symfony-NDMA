<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505072340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sensor ADD unit_id INT DEFAULT NULL, ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sensor ADD CONSTRAINT FK_BC8617B0F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('ALTER TABLE sensor ADD CONSTRAINT FK_BC8617B012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_BC8617B0F8BD700D ON sensor (unit_id)');
        $this->addSql('CREATE INDEX IDX_BC8617B012469DE2 ON sensor (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sensor DROP FOREIGN KEY FK_BC8617B0F8BD700D');
        $this->addSql('DROP TABLE unit');
        $this->addSql('ALTER TABLE sensor DROP FOREIGN KEY FK_BC8617B012469DE2');
        $this->addSql('DROP INDEX IDX_BC8617B0F8BD700D ON sensor');
        $this->addSql('DROP INDEX IDX_BC8617B012469DE2 ON sensor');
        $this->addSql('ALTER TABLE sensor DROP unit_id, DROP category_id');
    }
}
