<?php declare(strict_types=1);

namespace ChatApp;

require_once 'Message/Repo/MessageRepository.php';
require_once 'Message/Repo/Sqlite/SqliteMessageRepository.php';
require_once 'User/Api/UserService.php';
require_once 'User/WebApi/UserController.php';
require_once 'Common/Config.php';

use ChatApp\Common\Config;
use ChatApp\Message\Repo\Sqlite\SqliteMessageRepository;
use ChatApp\User\Api\UserService;
use ChatApp\User\WebApi\UserController;
use PDO;


$resourcePath = $_SERVER['PATH_INFO'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

routeRequests($resourcePath, $requestMethod);

function routeRequests(string $resourcePath, string $requestMethod): void
{
    if (isUserMessagesPath($resourcePath, $matches)) {
        $userId = (int)$matches[1];

        handleUserMessagesRequest($requestMethod, $userId);
    } else {
        http_response_code(404);
    }
}

function isUserMessagesPath(string $resourcePath, array &$matches = null): bool
{
    return preg_match('/^\/users\/(\d+)\/messages$/', $resourcePath, $matches) > 0;
}

function handleUserMessagesRequest(string $requestMethod, int $userId): void
{
    error_log("Handle [$requestMethod] messages for user [$userId]");

    $userController = newUserController();

    if ($requestMethod === 'GET') {
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode($userController->getMessages($userId));
    } else {
        http_response_code(405);
    }
}

function newUserController(): UserController
{
    $messageRepository = new SqliteMessageRepository(Config::SQLITE_PATH);
    initializeDatabase($messageRepository->getPdo());

    return new UserController(new UserService($messageRepository));
}

function initializeDatabase(PDO $pdo)
{
    $initScript = file_get_contents(Config::INIT_SCRIPT_PATH);
    $pdo->exec($initScript);
}