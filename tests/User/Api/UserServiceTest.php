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
    public function findMessages_ExistingUserId_ReturnsMessages(): void
    {
        $userService = Fixtures::newUserService([
            new Message(232, new DateTime('2000-02-03'), 'Bye Bob', 2233),
            new Message(0, new DateTime(), '', 0)
        ]);

        $result = $userService->findMessages(233);
        $this->assertEmpty($result->getErrors());

        $messages = $result->get();
        $this->assertCount(2, $messages);

        $this->assertEquals(
            new Message(232, new DateTime('2000-02-03'), 'Bye Bob', 2233),
            $messages[0]);
    }

    /** @test
     * @throws Exception
     */
    public function findMessages_MissingUserId_ReturnsErrors(): void
    {
        $userService = Fixtures::newUserService([]);

        $result = $userService->findMessages(233);
        $this->assertNull($result->get());

        $errors = $result->getErrors();

        $this->assertEquals(['Cannot find user with id 233'], $errors);
    }
}
