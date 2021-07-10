<?php declare(strict_types=1);

namespace NursingLog\Controller;

use NursingLog\ValueObject\DateTime;

class Session
{
    public function get() : void
    {
        echo json_encode(
            [
                [
                    'time' => '12:20',
                ],
                [
                    'time' => '10:20',
                ],
            ],
            JSON_THROW_ON_ERROR
        );
    }

    public function post() : void
    {
        DateTime::create();

        echo json_encode(
            [
                'time' => DateTime::create(),
            ],
            JSON_THROW_ON_ERROR
        );
    }
}
