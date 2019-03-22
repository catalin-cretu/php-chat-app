<?php declare(strict_types=1);

namespace ChatApp\User\Api;


use ChatApp\Fixtures;
use ChatApp\Message\Api\Message;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    /** @test
     * @throws Exception
     */
    public function findAllUsers_NoUsers_ReturnsEmptyResult(): void
    {
        $userService = Fixtures::newUserServiceWithUsers([]);

        $usersResult = $userService->findAllUsers();
        $this->assertEmpty($usersResult->getErrors());

        $users = $usersResult->get();
        $this->assertEmpty($users);
    }

    /** @test
     * @throws Exception
     */
    public function findAllUsers_ReturnsUsersResult(): void
    {
        $userService = Fixtures::newUserServiceWithUsers([
            new User(277),
            new User(278),
            new User(279),
        ]);

        $usersResult = $userService->findAllUsers();
        $this->assertEmpty($usersResult->getErrors());

        $users = $usersResult->get();
        $this->assertCount(3, $users);

        $this->assertEquals(new User(277), $users[0]);
    }

    /** @test
     * @throws Exception
     */
    public function findMessages_ExistingUserId_ReturnsMessagesResult(): void
    {
        $userService = Fixtures::newUserServiceWithMessages([
            new Message(2233, new DateTime('2000-02-03'), 'Bye Bob', 232),
            new Message(0, new DateTime(), '', 0)
        ]);

        $messagesResult = $userService->findMessages(233);
        $this->assertEmpty($messagesResult->getErrors());

        $messages = $messagesResult->get();
        $this->assertCount(2, $messages);

        $this->assertEquals(
            new Message(2233, new DateTime('2000-02-03'), 'Bye Bob', 232),
            $messages[0]);
    }

    /** @test
     * @throws Exception
     */
    public function findMessages_UserIdNotFound_ReturnsErrorsResult(): void
    {
        $userService = Fixtures::newUserServiceWithMessages([]);

        $errorsResult = $userService->findMessages(233);
        $this->assertNull($errorsResult->get());

        $errors = $errorsResult->getErrors();

        $this->assertEquals(['Cannot find user with id 233'], $errors);
    }
}