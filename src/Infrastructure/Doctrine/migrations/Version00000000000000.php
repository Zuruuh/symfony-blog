<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @api
 */
final class Version00000000000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                CREATE TABLE posts (
                    id UUID UNIQUE PRIMARY KEY,
                    title VARCHAR(255),
                    slug TEXT,
                    content TEXT
                )
                SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE posts');
    }
}
