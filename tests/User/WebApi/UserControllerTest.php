<?php

namespace ChatApp\User\WebApi;


use ChatApp\Fixtures;
use ChatApp\Message\Api\Message;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;


class UserControllerTest extends TestCase
{
    /** @test
     * @throws Exception
     */
    public function getMessages_ForUserId_ReturnsMessagesResponseWithMessages(): void
    {
        $userController = Fixtures::newUserController([
            new Message(0, new DateTime(), '', 5566),
            new Message(1, new DateTime(), '', 5566)
        ]);

        $messagesResponse = $userController->getMessages(5566);
        $this->assertEmpty($messagesResponse->errors);

        $this->assertNotEmpty($messagesResponse->messageViews);
        $this->assertContainsOnlyInstancesOf(MessageView::class, $messagesResponse->messageViews);
    }

    /** @test
     * @throws Exception
     */
    public function getMessages_MissingUserId_ReturnsMessagesResponseWithErrors(): void
    {
        $userController = Fixtures::newUserController([]);

        $messagesResponse = $userController->getMessages(2432);
        $this->assertEmpty($messagesResponse->messageViews);

        $this->assertNotEmpty($messagesResponse->errors);
        $this->assertStringContainsString('2432', $messagesResponse->errors[0]);
    }
}