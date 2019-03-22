<?php declare(strict_types=1);

namespace ChatApp;


require_once 'Common/Config.php';
require_once 'Message/Repo/MessageRepository.php';
require_once 'Message/Repo/Sqlite/SqliteMessageRepository.php';
require_once 'User/Api/UserService.php';
require_once 'User/Repo/Sqlite/SqliteUserRepository.php';
require_once 'User/WebApi/UserController.php';

use ChatApp\Common\Config;
use ChatApp\Message\Repo\Sqlite\SqliteMessageRepository;
use ChatApp\User\Api\UserService;
use ChatApp\User\Repo\Sqlite\SqliteUserRepository;
use ChatApp\User\WebApi\UserController;
use PDO;


function handleUserMessagesRequest(string $requestMethod, int $userIdPathParam): void
{
    error_log("Handle [$requestMethod] messages for user [$userIdPathParam]");

    $userController = newUserController();

    if ($requestMethod === 'GET') {
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode($userController->getMessages($userIdPathParam));
    } else {
        http_response_code(405);
    }
}

function handleUsersRequest(string $requestMethod): void
{
    error_log("Handle [$requestMethod] users");

    $userController = newUserController();

    if ($requestMethod === 'GET') {
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode($userController->getAllUsers());
    } else {
        http_response_code(405);
    }
}

function isUserMessagesPath(string $resourcePath, array &$matches = null): bool
{
    return preg_match('/^\/users\/(\d+)\/messages$/', $resourcePath, $matches) > 0;
}

function isUsersPath(string $resourcePath): bool
{
    return preg_match('/^\/users$/', $resourcePath) > 0;
}

function newUserController(): UserController
{
    $dataSource = initializeDatabase();

    $messageRepository = new SqliteMessageRepository($dataSource);
    $userRepository = new SqliteUserRepository($dataSource);


    return new UserController(new UserService($userRepository, $messageRepository));
}

function initializeDatabase(): PDO
{
    $dataSource = new PDO('sqlite:' . Config::SQLITE_PATH);

    $initScript = file_get_contents(Config::INIT_SCRIPT_PATH);
    $dataSource->exec($initScript);

    return $dataSource;
}