<?php declare(strict_types=1);

namespace App\Tests\Infrastructure;

use App\Tests\Infrastructure\Annotation\Skip;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    private ?Connection $connection = null;

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

    protected function tmpDir(): string
    {
        return sys_get_temp_dir();
    }

    protected function fixturesDir(): string
    {
        return self::getContainer()->getParameter('kernel.project_dir') . '/tests/Fixtures';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $reflection = new \ReflectionMethod($this, $this->name());
        $attributes = $reflection->getAttributes(Skip::class);

        if (!empty($attributes)) {
            $this->markTestSkipped('#[Skip]');
        }
    }
}
