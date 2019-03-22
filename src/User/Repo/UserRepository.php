<?php declare(strict_types=1);

namespace ChatApp\User\Repo;


use ChatApp\User\Api\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;
}