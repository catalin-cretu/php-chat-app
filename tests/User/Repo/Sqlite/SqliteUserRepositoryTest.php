<?php declare(strict_types=1);

namespace ChatApp\User\Repo\Sqlite;


use ChatApp\Common\Config;
use ChatApp\DB;
use ChatApp\User\Api\User;
use PDO;
use PHPUnit\Framework\TestCase;

class SqliteUserRepositoryTest extends TestCase
{
    /** @var SqliteUserRepository */
    private static $userRepository;

    public static function setUpBeforeClass(): void
    {
        $testDbUrl = 'sqlite:' . dirname(Config::SQLITE_PATH) . '/chat-app.test.db';
        $testDataSource = new PDO($testDbUrl);
        self::$userRepository = new SqliteUserRepository($testDataSource);

        $initScript = file_get_contents(Config::INIT_SCRIPT_PATH);
        self::$userRepository->getDataSource()->exec($initScript);
    }

    /** @test */
    public function findAll_ReturnsUsers(): void
    {
        $pdo = self::$userRepository->getDataSource();
        $firstUserId = DB::insertNewUser($pdo);
        $secondUserId = DB::insertNewUser($pdo);

        $users = self::$userRepository->findAll();

        $this->assertGreaterThanOrEqual(2, $users);
        $this->assertContainsOnlyInstancesOf(User::class, $users);

        $userIds = array_map(function (User $user) {
            return $user->getId();
        }, $users);
        $this->assertContains($firstUserId, $userIds);
        $this->assertContains($secondUserId, $userIds);
    }

    /** @test */
    public function exists_ExistingUserId_ReturnsTrue(): void
    {
        $this->assertFalse(self::$userRepository->exists(-234));

        $pdo = self::$userRepository->getDataSource();
        DB::insertNewUser($pdo);
        $userId = DB::insertNewUser($pdo);
        DB::insertNewUser($pdo);

        $this->assertTrue(self::$userRepository->exists($userId));
    }
}