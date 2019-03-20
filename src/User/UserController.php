<?php declare(strict_types=1);

namespace ChatApp\User;

class UserController
{

    public function getMessages(int $userId): string
    {
        return '[{
            "id": 1,
            "user": 321,
            "timestamp": "2000-01-01T10:01:00.822",
            "message": "Hello!"
          }, {
            "id": 2,
            "user": 321,
            "timestamp": "2000-01-01T10:01:01.233",
            "message": "how r u doing?"
          }, {
            "id": 3,
            "user": 300,
            "timestamp": "2000-05-02T12:31:11.102",
            "message": "oh, no!"
          }]';
    }
}