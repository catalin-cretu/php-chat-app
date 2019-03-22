<?php declare(strict_types=1);

namespace ChatApp\User\WebApi;


use ChatApp\Fixtures;
use ChatApp\Message\Api\Message;
use ChatApp\User\Api\User;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../src/User/WebApi/CreateUserMessage.php';

class UserControllerTest extends TestCase
{
    /** @test
     * @throws Exception
     */
    public function getAllUsers_ReturnsUsersResponse(): void
    {
        $userController = Fixtures::newUserController([new User(0)]);

        $usersResponse = $userController->getAllUsers();
        $this->assertEmpty($usersResponse->errors);

        $this->assertNotEmpty($usersResponse->users);
        $this->assertContainsOnlyInstancesOf(UserView::class, $usersResponse->users);
    }

    /** @test
     * @throws Exception
     */
    public function getMessages_ForUserId_ReturnsMessagesResponseWithMessages(): void
    {
        $userController = Fixtures::newUserController([new User(5566)], [
            new Message(5566, new DateTime(), '', 0),
        ]);

        $messagesResponse = $userController->getMessages(5566);
        $this->assertEmpty($messagesResponse->errors);

        $this->assertNotEmpty($messagesResponse->messages);
        $this->assertContainsOnlyInstancesOf(MessageView::class, $messagesResponse->messages);
    }

    /** @test
     * @throws Exception
     */
    public function getMessages_MissingUserId_ReturnsMessagesResponseWithErrors(): void
    {
        $userController = Fixtures::newUserController([], []);

        $messagesResponse = $userController->getMessages(2432);
        $this->assertEmpty($messagesResponse->messages);

        $this->assertNotEmpty($messagesResponse->errors);
        $this->assertStringContainsString('2432', $messagesResponse->errors[0]);
    }

    /** @test
     * @throws Exception
     */
    public function createUserMessage_ForUserId_ReturnsCreateUserMessageResponse(): void
    {
        $userController = Fixtures::newUserController([new User(0)]);

        $createUserMessageResponse = $userController->createUserMessage(0, new CreateUserMessageRequest(0, ';)'));

        $this->assertInstanceOf(MessageView::class, $createUserMessageResponse->message);
        $this->assertEmpty($createUserMessageResponse->errors);
    }

    /** @test
     * @throws Exception
     */
    public function createUserMessage_MissingUserId_ReturnsCreateUserMessageResponseWithErrors(): void
    {
        $userController = Fixtures::newUserController();

        $createUserMessageResponse = $userController->createUserMessage(2342, new CreateUserMessageRequest());

        $this->assertNotEmpty($createUserMessageResponse->errors);
        $this->assertStringContainsString('2342', $createUserMessageResponse->errors[0]);
    }
}