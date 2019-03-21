<?php declare(strict_types=1);

namespace ChatApp\Message\Repo\Sqlite;

require_once __DIR__ . '/../../Api/Message.php';

use ChatApp\Message\Api\Message;
use ChatApp\Message\Repo\MessageRepository;
use DateTime;
use Exception;
use PDO;

class SqliteMessageRepository implements MessageRepository
{
    private $pdo;

    public function __construct(string $dbPath)
    {
        $this->pdo = new PDO('sqlite:' . $dbPath);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @param int $userId
     * @return Message[]
     * @throws Exception
     */
    public function findByUserId(int $userId): array
    {
        $messagesStatement = $this->pdo->prepare('select * from MESSAGE where USER_ID = :userId');
        $messagesStatement->execute(['userId' => $userId]);

        $messages = [];
        foreach ($messagesStatement as $row) {
            $messages[] = new Message(
                (int)$row['USER_ID'],
                new DateTime($row['TIMESTAMP']),
                $row['MESSAGE'],
                (int)$row['ID']);
        }
        return $messages;
    }
}