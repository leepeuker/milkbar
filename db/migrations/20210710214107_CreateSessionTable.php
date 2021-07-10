<?php declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateSessionTable extends AbstractMigration
{
    public function down() : void
    {
        $this->execute('DROP TABLE `session`');
    }

    public function up() : void
    {
        $this->execute(
            <<<SQL
            CREATE TABLE `session` (
                `id` CHAR(36) NOT NULL,
                `time` TIMESTAMP NOT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB
            SQL
        );
    }
}
