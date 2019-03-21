<?php declare(strict_types=1);

namespace ChatApp\Message\Repo\Sqlite;


use ChatApp\Common\Config;
use ChatApp\DB;
use ChatApp\Message\Api\Message;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class SqliteMessageRepositoryTest extends TestCase
{
    /** @var SqliteMessageRepository */
    private static $messageRepository;

    public static function setUpBeforeClass(): void
    {
        $testDbPath = dirname(Config::SQLITE_PATH) . '/chat-app.test.db';
        self::$messageRepository = new SqliteMessageRepository($testDbPath);

        $initScript = file_get_contents(Config::INIT_SCRIPT_PATH);
        self::$messageRepository->getPdo()->exec($initScript);
    }

    /** @test
     * @throws Exception
     */
    public function findByUserId_MessagesWithUserId_ReturnsMessages(): void
    {
        $pdo = self::$messageRepository->getPdo();
        $lastUserId = DB::insertNewUser($pdo);

        DB::insertMessage($pdo, $lastUserId - 1, '2019-03-21T10:12:42.477+00:00', ':|');
        DB::insertMessage($pdo, $lastUserId, '2019-03-21T20:12:42.477+00:00', 'Hell');
        DB::insertMessage($pdo, $lastUserId, '2019-03-21T20:12:43.175+00:00', '0, ');
        $lastMessageId = DB::insertMessage($pdo, $lastUserId, '2019-03-21T20:12:44.002+00:00', 'World!');

        $messages = self::$messageRepository->findByUserId($lastUserId);
        $this->assertCount(3, $messages);

        $this->assertEquals(
            new Message($lastUserId, new DateTime('2019-03-21T20:12:44.002+00:00'), 'World!', $lastMessageId),
            $messages[2]
        );
    }

    /** @test
     * @throws Exception
     */
    public function findByUserId_MissingUserId_ReturnsEmpty(): void
    {
        $pdo = self::$messageRepository->getPdo();
        $lastUserId = DB::insertNewUser($pdo);
        DB::insertMessage($pdo, $lastUserId, '2011-01-11T10:11:42.477+00:00', 'HI');

        $messages = self::$messageRepository->findByUserId(-3453453);

        $this->assertEmpty($messages);
    }
}