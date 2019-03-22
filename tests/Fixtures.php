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
     * @return UserController
     */
    public static function newUserControllerWithUsers(array $users): UserController
    {
        return new UserController(self::newUserServiceWithUsers($users));
    }

    /**
     * @param Message[] $messages
     * @return UserController
     */
    public static function newUserControllerWithMessages(array $messages): UserController
    {
        return new UserController(self::newUserServiceWithMessages($messages));
    }

    /**
     * @param Message[] $messages
     * @return UserService
     */
    public static function newUserServiceWithMessages(array $messages): UserService
    {
        return new UserService(new DefaultUserRepository(), new DefaultMessageRepository($messages));
    }

    /**
     * @param User[] $users
     * @return UserService
     */
    public static function newUserServiceWithUsers(array $users): UserService
    {
        return new UserService(new DefaultUserRepository($users), new DefaultMessageRepository());
    }
}