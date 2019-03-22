<?php declare(strict_types=1);

namespace ChatApp\User\Repo\Sqlite;

require_once __DIR__ . '/../../Api/User.php';
require_once __DIR__ . '/../../Repo/UserRepository.php';
require_once __DIR__ . '/../../../Common/Repo/DataSource.php';

use ChatApp\Common\Repo\DataSource;
use ChatApp\User\Api\User;
use ChatApp\User\Repo\UserRepository;
use PDO;

class SqliteUserRepository implements UserRepository, DataSource
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
     * @return User[]
     */
    public function findAll(): array
    {
        $usersStatement = $this->dataSource->query('select * from USER');
        $users = [];

        foreach ($usersStatement as $row) {
            $users[] = new User((int)$row['ID']);
        }
        return $users;
    }
}