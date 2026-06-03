<?php declare(strict_types=1);

namespace App\Tests\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    protected function fixturesDir(): string
    {
        return self::getContainer()->getParameter('kernel.project_dir') . '/tests/Fixtures';
    }

    protected function request(string $method, string $url): Response
    {
        $client = static::createClient();
        $client->request($method, $url);
        $response = $client->getResponse();

        return new Response($response, $this);
    }
}
