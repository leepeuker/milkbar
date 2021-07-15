<?php

use Phinx\Migration\AbstractMigration;

class AddUserTimeUntilNextMeal extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
            ALTER TABLE `user` DROP COLUMN timeUntilNextMeal;
            SQL
        );
    }

    public function up() : void
    {
        $this->execute(
            <<<SQL
            ALTER TABLE `user` ADD COLUMN timeUntilNextMeal SMALLINT UNSIGNED DEFAULT 4 AFTER password;
            SQL
        );
    }
}
