<?php declare(strict_types=1);

namespace App\Tests\Infrastructure;

use App\Service\Fs\FsHelper;
use App\Tests\Infrastructure\Attribute\Skip;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    private ?Connection $connection = null;
    private ?Filesystem $filesystem = null;

    public static function setUpBeforeClass(): void
    {
        $ref = new \ReflectionClass(static::class);
        if ($ref->getAttributes(Skip::class) !== []) {
            self::markTestSkipped('#[Skip]');
        }
    }

    protected function setUp(): void
    {
        $ref = new \ReflectionMethod($this, $this->name());
        if ($ref->getAttributes(Skip::class) !== []) {
            $this->markTestSkipped('#[Skip]');
        }

        parent::setUp();
    }

    protected function connectionPsql(): Connection
    {
        if ($this->connection === null) {
            $conn = static::getContainer()->get(\Doctrine\DBAL\Connection::class);
            $this->connection = new Connection($conn);
        }
        return $this->connection;
    }

    protected function request(string $method, string $url, ?string $body = null): Response
    {
        static::ensureKernelShutdown(); // Принудительно гасим ядро. Кажется почему-то \App\Infrastructure\RequestResolver запускает его

        $headers = [];
        if ($body !== null) {
            $headers['CONTENT_TYPE'] = 'application/json';
        }

        $client = static::createClient();
        $client->request($method, $url, server: $headers, content: $body);
        $response = $client->getResponse();

        return new Response($response, $this);
    }

    protected function filesystem(): Filesystem
    {
        if ($this->filesystem === null) {
            $this->filesystem = new Filesystem(new FsHelper());
        }
        return $this->filesystem;
    }
}
