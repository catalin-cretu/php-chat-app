<?php declare(strict_types=1);

namespace ChatApp\User\Repo;

use ChatApp\User\Api\User;

class DefaultUserRepository implements UserRepository
{
    /** @var User[] */
    private $users;

    /**
     * @param User[] $users
     */
    public function __construct(array $users = [])
    {
        $this->users = $users;
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->users;
    }

    public function exists(int $userId): bool
    {
        foreach ($this->users as $user) {
            if ($user->getId() === $userId) {
                return true;
            }
        }
        return false;
    }
}