<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190228101027 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE music ADD user_id INT DEFAULT NULL, DROP created_by');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_CD52224AA76ED395 ON music (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224AA76ED395');
        $this->addSql('DROP INDEX IDX_CD52224AA76ED395 ON music');
        $this->addSql('ALTER TABLE music ADD created_by VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP user_id');
    }
}
