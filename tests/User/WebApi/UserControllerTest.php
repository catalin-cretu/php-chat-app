<?php declare(strict_types=1);

namespace ChatApp\User\WebApi;


use ChatApp\Fixtures;
use ChatApp\Message\Api\Message;
use ChatApp\User\Api\User;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;


class UserControllerTest extends TestCase
{
    /** @test
     * @throws Exception
     */
    public function getAllUsers_ReturnsUsersResponse(): void
    {
        $userController = Fixtures::newUserControllerWithUsers([
            new User(0)
        ]);

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
        $userController = Fixtures::newUserControllerWithMessages([
            new Message(0, new DateTime(), '', 0),
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
        $userController = Fixtures::newUserControllerWithMessages([]);

        $messagesResponse = $userController->getMessages(2432);
        $this->assertEmpty($messagesResponse->messages);

        $this->assertNotEmpty($messagesResponse->errors);
        $this->assertStringContainsString('2432', $messagesResponse->errors[0]);
    }
}