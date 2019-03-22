<?php declare(strict_types=1);

namespace ChatApp\Common\Repo;


use PDO;

interface DataSource
{
    public function getDataSource(): PDO;
}