<?php declare(strict_types=1);

namespace ChatApp\Message\Repo\Sqlite;

require_once __DIR__ . '/../../Api/Message.php';
require_once __DIR__ . '/../../../Common/Repo/DataSource.php';

use ChatApp\Common\Repo\DataSource;
use ChatApp\Message\Api\Message;
use ChatApp\Message\Repo\MessageRepository;
use DateTime;
use Exception;
use PDO;

class SqliteMessageRepository implements MessageRepository, DataSource
{
    private $dataSource;

    public function __construct(PDO $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function getDataSource(): PDO
    {
        return $this->dataSource;
    }

    /**
     * @param int $userId
     * @return Message[]
     * @throws Exception
     */
    public function findByUserId(int $userId): array
    {
        $messagesStatement = $this->dataSource->prepare('select * from MESSAGE where USER_ID = :userId');
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