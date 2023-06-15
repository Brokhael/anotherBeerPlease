<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615113506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration will create the table in the database called `beer` 🍺 for the API';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE dispenser (
                id INT NOT NULL AUTO_INCREMENT,
                flow_volume DOUBLE PRECISION NOT NULL,
                status VARCHAR(255) NOT NULL,
                total_time_open INT NOT NULL,
                total_money DOUBLE PRECISION NOT NULL,
                usage_count INT NOT NULL,
                last_open_time DATETIME DEFAULT NULL,
                price DOUBLE PRECISION NOT NULL DEFAULT 1,
                active BOOLEAN DEFAULT TRUE,
                PRIMARY KEY(id)
            )'
        );

        $this->addSql(
            'CREATE TABLE revenue (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                dispenser_id INT NOT NULL,
                service_time DOUBLE NOT NULL,
                service_money DOUBLE NOT NULL,
                FOREIGN KEY (dispenser_id) REFERENCES dispenser (id)
            )'
        );

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE dispenser');
        $this->addSql('DROP TABLE revenue');
    }
}
