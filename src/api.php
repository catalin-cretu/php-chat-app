<?php declare(strict_types=1);
require_once 'User/UserController.php';

use ChatApp\User\UserController;


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

    $userController = new UserController();

    if ($requestMethod === 'GET') {
        echo $userController->getMessages($userId);
    } else {
        http_response_code(405);
    }
}