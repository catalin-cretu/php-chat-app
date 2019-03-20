<?php declare(strict_types=1);

use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private const ROOT_URL = 'http://localhost:8008/api.php';

    /** @var GuzzleHttp\Client */
    private static $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new GuzzleHttp\Client();
    }

    /** @test */
    public function request_UnknownResource_ReturnsNotFound(): void
    {
        $this->expectException(ClientException::class);
        $this->getExpectedExceptionMessage('404 Not Found');

        self::$client->get('http://localhost:8008/notfound');
    }

    /** @test */
    public function request_UnknownApiPath_ReturnsNotFound(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('404 Not Found');

        self::$client->get(self::ROOT_URL . '/notfound');
    }

    /** @test */
    public function request_UnmappedOperation_ReturnsNotAllowed(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('405 Method Not Allowed');

        self::$client->delete(self::ROOT_URL . '/users/321/messages');
    }

    /** @test */
    public function getUserMessages_ForUserId_ReturnsMessages(): void
    {
        $response = self::$client->get(self::ROOT_URL . '/users/321/messages');

        $this->assertEquals(200, $response->getStatusCode());

        $messages = json_decode($response->getBody()->getContents());
        $this->assertCount(3, $messages);

        $this->assertNotNull($messages[0]->id);
    }
}