<?php declare(strict_types=1);

namespace App\Tests\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    protected function request(string $method, string $url, ?string $body = null): Response
    {
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
}
