<?php

use Phinx\Migration\AbstractMigration;

class AddUserRelationToSessions extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
            ALTER TABLE `session` DROP COLUMN user_id;
            SQL
        );
    }

    public function up() : void
    {
        $this->execute(
            <<<SQL
            ALTER TABLE `session` ADD COLUMN user_id INT(10) UNSIGNED DEFAULT NULL AFTER id;
            SQL
        );
    }
}
