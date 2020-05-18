<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505080921 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sensor_data DROP FOREIGN KEY FK_801762CC3900C4BF');
        $this->addSql('DROP INDEX UNIQ_801762CC3900C4BF ON sensor_data');
        $this->addSql('ALTER TABLE sensor_data ADD value VARCHAR(255) NOT NULL, ADD date DATETIME NOT NULL, DROP data, CHANGE sensor_id_id sensor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sensor_data ADD CONSTRAINT FK_801762CCA247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id)');
        $this->addSql('CREATE INDEX IDX_801762CCA247991F ON sensor_data (sensor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sensor_data DROP FOREIGN KEY FK_801762CCA247991F');
        $this->addSql('DROP INDEX IDX_801762CCA247991F ON sensor_data');
        $this->addSql('ALTER TABLE sensor_data ADD data JSON NOT NULL, DROP value, DROP date, CHANGE sensor_id sensor_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sensor_data ADD CONSTRAINT FK_801762CC3900C4BF FOREIGN KEY (sensor_id_id) REFERENCES sensor (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_801762CC3900C4BF ON sensor_data (sensor_id_id)');
    }
}
