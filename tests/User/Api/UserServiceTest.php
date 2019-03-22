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
        $userService = Fixtures::newUserService([]);

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
        $userService = Fixtures::newUserService([
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
        $userService = Fixtures::newUserService([new User(233)], [
            new Message(233, new DateTime('2000-02-03'), 'Bye Bob', 0),
            new Message(233, new DateTime(), '', 0)
        ]);

        $messagesResult = $userService->findMessages(233);
        $this->assertEmpty($messagesResult->getErrors());

        $messages = $messagesResult->get();
        $this->assertCount(2, $messages);

        $this->assertEquals(
            new Message(233, new DateTime('2000-02-03'), 'Bye Bob', 0),
            $messages[0]);
    }

    /** @test
     * @throws Exception
     */
    public function findMessages_NoMessagesForUserId_ReturnsEmptyResult(): void
    {
        $userService = Fixtures::newUserService([
            new User(1), new User(2), new User(345)
        ], [
            new Message(1, new DateTime(), '', 0),
            new Message(2, new DateTime(), '', 0)
        ]);

        $messagesResult = $userService->findMessages(345);
        $this->assertEmpty($messagesResult->getErrors());
        $this->assertEmpty($messagesResult->get());
    }

    /** @test
     * @throws Exception
     */
    public function findMessages_UserIdNotFound_ReturnsErrorsResult(): void
    {
        $userService = Fixtures::newUserService(
            [new User(1)],
            [new Message(1, new DateTime(), '', 0)]
        );

        $errorsResult = $userService->findMessages(233);
        $this->assertNull($errorsResult->get());

        $errors = $errorsResult->getErrors();

        $this->assertEquals(['Cannot find user with id 233'], $errors);
    }
}