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

    /** @test
     * @throws Exception
     */
    public function createMessage_NullSenderId_ReturnsErrorsResult(): void
    {
        $userService = Fixtures::newUserService([new User(0)]);

        $errorsResult = $userService->createMessage(0, null, 'a');
        $this->assertNull($errorsResult->get());

        $errors = $errorsResult->getErrors();

        $this->assertEquals(["Sender user id ('sender') must not be blank"], $errors);
    }

    /** @test
     * @throws Exception
     */
    public function createMessage_BlankMessage_ReturnsErrorsResult(): void
    {
        $userService = Fixtures::newUserService([new User(0)]);

        $errorsResult = $userService->createMessage(0, 0, null);
        $this->assertNull($errorsResult->get());

        $errors = $errorsResult->getErrors();

        $this->assertEquals(["Content of message ('message') must not be blank"], $errors);
        $this->assertEquals(
            ["Content of message ('message') must not be blank"],
            $userService->createMessage(0, 0, '   ')->getErrors());
    }

    /** @test
     * @throws Exception
     */
    public function createMessage_UserIdNotFound_ReturnsErrorsResult(): void
    {
        $userService = Fixtures::newUserService(
            [new User(1), new User(2)]
        );

        $errorsResult = $userService->createMessage(122, 2, 'aa');
        $this->assertNull($errorsResult->get());

        $errors = $errorsResult->getErrors();

        $this->assertEquals(['Cannot find user with id 122'], $errors);
    }

    /** @test
     * @throws Exception
     */
    public function createMessage_SenderIdNotFound_ReturnsErrorsResult(): void
    {
        $userService = Fixtures::newUserService(
            [new User(1), new User(2)]
        );

        $errorsResult = $userService->createMessage(1, 2342, 'aa');
        $this->assertNull($errorsResult->get());

        $errors = $errorsResult->getErrors();

        $this->assertEquals(['Cannot find sender user with id 2342'], $errors);
    }

    /** @test
     * @throws Exception
     */
    public function createMessage_ReturnsMessageIdResult(): void
    {
        $userService = Fixtures::newUserService(
            [new User(1)]
        );

        $messageIdResult = $userService->createMessage(1, 1, 'new');
        $this->assertEmpty($messageIdResult->getErrors());

        $messageResult = $userService->findMessages(1);

        $message = $messageResult->get()[0];
        $this->assertNotNull($message->getId());
        $this->assertEquals(1, $message->getUserId());
        $this->assertEquals('new', $message->getMessage());
    }
}