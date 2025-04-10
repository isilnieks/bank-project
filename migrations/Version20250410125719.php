<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410125719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, balance NUMERIC(19, 4) NOT NULL, currency VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7D3656A419EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, from_account_id INT NOT NULL, to_account_id INT NOT NULL, from_currency VARCHAR(3) NOT NULL, exchange_rate NUMERIC(15, 6) DEFAULT NULL, from_amount NUMERIC(15, 2) NOT NULL, to_amount NUMERIC(15, 2) NOT NULL, to_currency VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_723705D1B0CF99BD (from_account_id), INDEX IDX_723705D1BC58BDC7 (to_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account ADD CONSTRAINT FK_7D3656A419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B0CF99BD FOREIGN KEY (from_account_id) REFERENCES account (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction ADD CONSTRAINT FK_723705D1BC58BDC7 FOREIGN KEY (to_account_id) REFERENCES account (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE account DROP FOREIGN KEY FK_7D3656A419EB6921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1B0CF99BD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1BC58BDC7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE account
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE client
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transaction
        SQL);
    }
}
