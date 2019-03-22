<?php declare(strict_types=1);

namespace ChatApp;


require_once 'Common/Config.php';
require_once 'Message/Repo/MessageRepository.php';
require_once 'Message/Repo/Sqlite/SqliteMessageRepository.php';
require_once 'User/Api/UserService.php';
require_once 'User/Repo/Sqlite/SqliteUserRepository.php';
require_once 'User/WebApi/UserController.php';
require_once 'User/WebApi/CreateUserMessage.php';

use ChatApp\Common\Config;
use ChatApp\Message\Repo\Sqlite\SqliteMessageRepository;
use ChatApp\User\Api\UserService;
use ChatApp\User\Repo\Sqlite\SqliteUserRepository;
use ChatApp\User\WebApi\CreateUserMessageRequest;
use ChatApp\User\WebApi\UserController;
use PDO;


function handleUserMessagesRequest(string $requestMethod, int $userIdPathParam): void
{
    error_log("Handle [$requestMethod] messages for user [$userIdPathParam]");

    $userController = newUserController();

    if ($requestMethod === 'GET') {
        $messagesResponse = $userController->getMessages($userIdPathParam);
        echo toEncodedResponse($messagesResponse);

    } elseif ($requestMethod === 'POST') {
        $createUserMessageRequest = getCreateUserMessageRequest();
        $createUserMessageResponse =
            $userController->createUserMessage($userIdPathParam, $createUserMessageRequest);

        echo toEncodedResponse($createUserMessageResponse);

    } else {
        http_response_code(405);
    }
    addJsonContentTypeHeader();
}

function getCreateUserMessageRequest(): CreateUserMessageRequest
{
    $requestBody = file_get_contents('php://input');
    $json = json_decode($requestBody, true);

    $sender = null;
    if (array_key_exists('sender', $json)) {
        $sender = (int)$json['sender'];
    }
    $message = null;
    if (array_key_exists('message', $json)) {
        $message = $json['message'];
    }
    return new CreateUserMessageRequest($sender, $message);
}

function handleUsersRequest(string $requestMethod): void
{
    error_log("Handle [$requestMethod] users");

    $userController = newUserController();

    if ($requestMethod === 'GET') {
        echo json_encode($userController->getAllUsers());
    } else {
        http_response_code(405);
    }
    addJsonContentTypeHeader();
}

function isUserMessagesPath(string $resourcePath, array &$matches = null): bool
{
    return preg_match('/^\/users\/(\d+)\/messages$/', $resourcePath, $matches) > 0;
}

function isUsersPath(string $resourcePath): bool
{
    return preg_match('/^\/users$/', $resourcePath) > 0;
}

function toEncodedResponse(object $response): string
{
    if (!empty($response->errors)) {
        http_response_code(400);
    }
    return json_encode($response);
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

function addJsonContentTypeHeader(): void
{
    header('Content-Type: application/json; charset=UTF-8');
}