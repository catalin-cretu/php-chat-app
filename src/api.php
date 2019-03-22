<?php declare(strict_types=1);

namespace ChatApp;


require_once 'handler.php';

$resourcePath = $_SERVER['PATH_INFO'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if (isUserMessagesPath($resourcePath, $matches)) {
    $userIdPathParam = (int)$matches[1];
    handleUserMessagesRequest($requestMethod, $userIdPathParam);

} elseif (isUsersPath($resourcePath)) {
    handleUsersRequest($requestMethod);

} else {
    http_response_code(404);
}