<?php

use Phinx\Migration\AbstractMigration;

class AddMinutesLeftAndRight extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
            ALTER TABLE `session` DROP COLUMN minutesLeft;
            ALTER TABLE `session` DROP COLUMN minutesRight;
            SQL
        );
    }

    public function up() : void
    {
        $this->execute(
            <<<SQL
            ALTER TABLE `session` ADD COLUMN minutesLeft SMALLINT UNSIGNED AFTER time;
            ALTER TABLE `session` ADD COLUMN minutesRight SMALLINT UNSIGNED AFTER minutesLeft;
            SQL
        );
    }
}
