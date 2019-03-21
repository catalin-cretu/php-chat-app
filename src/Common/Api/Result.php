<?php declare(strict_types=1);

namespace ChatApp\Common\Api;


class Result
{
    /** @var array|object */
    private $okValue;

    /** @var string[] */
    private $errors;

    /**
     * Result constructor.
     * @param array|object $okValue
     * @param string[] $errors
     */
    private function __construct($okValue, array $errors = [])
    {
        $this->okValue = $okValue;
        $this->errors = $errors;
    }

    /**
     * @param array|object $messages
     * @return Result result with OK value
     */
    public static function ok($okValue): Result
    {
        return new Result($okValue);
    }

    /**
     * @param string[] $errors
     * @return Result result with errors
     */
    public static function errors(array $errors): Result
    {
        return new Result(null, $errors);
    }

    /**
     * @return array|object
     */
    public function get()
    {
        return $this->okValue;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}