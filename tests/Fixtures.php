<?php declare(strict_types=1);

namespace ChatApp;


use ChatApp\Message\Api\Message;
use ChatApp\Message\Repo\DefaultMessageRepository;
use ChatApp\User\Api\User;
use ChatApp\User\Api\UserService;
use ChatApp\User\Repo\DefaultUserRepository;
use ChatApp\User\WebApi\UserController;


class Fixtures
{
    /**
     * @param User[] $users
     * @param Message[] $messages
     * @return UserController
     */
    public static function newUserController(array $users = [], array $messages = []): UserController
    {
        return new UserController(self::newUserService($users, $messages));
    }

    /**
     * @param User[] $users
     * @param Message[] $messages
     * @return UserService
     */
    public static function newUserService(array $users = [], array $messages = []): UserService
    {
        return new UserService(
            new DefaultUserRepository($users),
            new DefaultMessageRepository($messages)
        );
    }
}