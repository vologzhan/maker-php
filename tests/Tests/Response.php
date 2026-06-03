<?php declare(strict_types=1);

namespace App\Tests\Tests;

use PHPUnit\Framework\Constraint\JsonMatches;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final readonly class Response
{
    public function __construct(
        private \Symfony\Component\HttpFoundation\Response $response,
        private WebTestCase $testCase,
    ) {}

    public function expectedHeader(string $headerName, string $expectedValue): self
    {
        $this->testCase::assertResponseHeaderSame($headerName, $expectedValue);
        return $this;
    }

    public function expectedCode(int $expectedCode): self
    {
        $this->testCase::assertResponseStatusCodeSame($expectedCode);
        return $this;
    }

    public function expectedJsonContent(string $expectedJson): self
    {
        $this->testCase::assertThat($this->response->getContent(), new JsonMatches($expectedJson));
        return $this;
    }
}
