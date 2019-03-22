<?php declare(strict_types=1);

use ChatApp\Common\Config;
use ChatApp\DB;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class ApiFT extends TestCase
{
    private const ROOT_URL = 'http://localhost:8008/api.php';

    /** @var GuzzleHttp\Client */
    private static $client;

    /** @var PDO */
    private static $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$client = new GuzzleHttp\Client();
        self::$pdo = new PDO('sqlite:' . Config::SQLITE_PATH);
    }

    /** @test */
    public function request_UnknownResource_ReturnsNotFoundStatus(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('404 Not Found');

        self::$client->get('http://localhost:8008/notfound');
    }

    /** @test */
    public function request_UnknownApiPath_ReturnsNotFoundStatus(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('404 Not Found');

        self::$client->get(self::ROOT_URL . '/notfound');
    }

    /** @test */
    public function getAllUsers_UnmappedOperation_ReturnsNotAllowedStatus(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('405 Method Not Allowed');

        self::$client->post(self::ROOT_URL . '/users');
    }

    /** @test */
    public function getAllUsers_ReturnsOkStatus(): void
    {
        $response = self::$client->get(self::ROOT_URL . '/users');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function getAllUsers_ReturnsJson(): void
    {
        $userId = DB::insertNewUser(self::$pdo);

        $response = self::$client->get(self::ROOT_URL . "/users");

        $this->assertContains('application/json; charset=UTF-8', $response->getHeader('Content-Type'));
        $responseJson = $response->getBody()->getContents();

        $this->assertJson($responseJson);
        $this->assertStringContainsString(":$userId", $responseJson);
    }

    /** @test */
    public function getUserMessages_UnmappedOperation_ReturnsNotAllowedStatus(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('405 Method Not Allowed');

        self::$client->delete(self::ROOT_URL . '/users/321/messages');
    }

    /** @test */
    public function getUserMessages_ForUserId_ReturnsOkStatus(): void
    {
        $userId = DB::insertNewUser(self::$pdo);
        $response = self::$client->get(self::ROOT_URL . "/users/$userId/messages");

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function getUserMessages_ForUserId_ReturnsJson(): void
    {
        $userId = DB::insertNewUser(self::$pdo);
        DB::insertMessage(self::$pdo, $userId, '2018-12-12T00:00:00.000+00:00', 'Happy Old Year!');

        $response = self::$client->get(self::ROOT_URL . "/users/$userId/messages");

        $this->assertContains('application/json; charset=UTF-8', $response->getHeader('Content-Type'));
        $responseJson = $response->getBody()->getContents();

        $this->assertJson($responseJson);
        $this->assertStringContainsString(':"Happy Old Year!"', $responseJson);
        $this->assertStringContainsString(':"2018-12-12T00:00:00+00:00"', $responseJson);
    }
}