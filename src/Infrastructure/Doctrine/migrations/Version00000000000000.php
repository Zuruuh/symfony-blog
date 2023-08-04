<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version00000000000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
    }

    public function down(Schema $schema): void
    {
    }
}
