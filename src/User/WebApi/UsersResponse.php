<?php declare(strict_types=1);

namespace ChatApp\User\WebApi;


use ChatApp\Common\WebApi\ErrorsResponse;

require_once __DIR__ . '/../../Common/WebApi/ErrorsResponse.php';

class UsersResponse extends ErrorsResponse
{
    /** @var UserView[] */
    public $users;

    /**
     * @param UserView[] $userViews
     * @param string[] $errors
     */
    public function __construct(array $userViews = [], array $errors = [])
    {
        $this->users = $userViews;
        $this->errors = $errors;
    }
}

class UserView
{
    /** @var int */
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}