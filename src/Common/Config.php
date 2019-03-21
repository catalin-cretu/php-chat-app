<?php declare(strict_types=1);

namespace ChatApp\Common;


class Config
{
    public const SQLITE_PATH = __DIR__ . '/../../data/chat-app.db';

    public const INIT_SCRIPT_PATH = __DIR__ . '/../../db/init.sql';
}