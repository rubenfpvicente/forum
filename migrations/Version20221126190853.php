<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221126190853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE access_tokens (id VARCHAR(255) NOT NULL, client_id VARCHAR(255) DEFAULT NULL, expiry_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_identifier VARCHAR(255) NOT NULL, INDEX IDX_58D184BC19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scope_tokens (token_id VARCHAR(255) NOT NULL, scope_id VARCHAR(255) NOT NULL, INDEX IDX_67767ED641DEE7B9 (token_id), INDEX IDX_67767ED6682B5931 (scope_id), PRIMARY KEY(token_id, scope_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id VARCHAR(255) NOT NULL, access_token_id VARCHAR(255) DEFAULT NULL, expiry_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_9BACE7E12CCB2688 (access_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scopes (id VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE access_tokens ADD CONSTRAINT FK_58D184BC19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE scope_tokens ADD CONSTRAINT FK_67767ED641DEE7B9 FOREIGN KEY (token_id) REFERENCES access_tokens (id)');
        $this->addSql('ALTER TABLE scope_tokens ADD CONSTRAINT FK_67767ED6682B5931 FOREIGN KEY (scope_id) REFERENCES scopes (id)');
        $this->addSql('ALTER TABLE refresh_tokens ADD CONSTRAINT FK_9BACE7E12CCB2688 FOREIGN KEY (access_token_id) REFERENCES access_tokens (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_tokens DROP FOREIGN KEY FK_58D184BC19EB6921');
        $this->addSql('ALTER TABLE scope_tokens DROP FOREIGN KEY FK_67767ED641DEE7B9');
        $this->addSql('ALTER TABLE scope_tokens DROP FOREIGN KEY FK_67767ED6682B5931');
        $this->addSql('ALTER TABLE refresh_tokens DROP FOREIGN KEY FK_9BACE7E12CCB2688');
        $this->addSql('DROP TABLE access_tokens');
        $this->addSql('DROP TABLE scope_tokens');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE scopes');
    }
}
