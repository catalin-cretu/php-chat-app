<?php declare(strict_types=1);

namespace ChatApp;


use PDO;

class DB
{
    public static function insertNewUser(PDO $pdo): int
    {
        $pdo->exec('insert into USER default values');

        return (int)$pdo->lastInsertId();
    }

    public static function insertMessage(PDO $pdo, int $userId, string $timestamp, string $message): int
    {
        $pdo->exec(
            "insert into MESSAGE values (null, $userId, '$timestamp', '$message')");

        return (int)$pdo->lastInsertId();
    }
}