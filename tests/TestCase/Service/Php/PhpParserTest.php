<?php declare(strict_types=1);

namespace App\Tests\TestCase\Service\Php;

use App\Dto\Php\ArgumentDto;
use App\Dto\Php\AttributeDto;
use App\Dto\Php\ClassDto;
use App\Dto\Php\MethodDto;
use App\Dto\Php\NodeDto;
use App\Service\Php\PhpParser;
use App\Tests\Infrastructure\ApiTestCase;
use Symfony\Component\Routing\Attribute\Route;

final class PhpParserTest extends ApiTestCase
{
    public function test(): void
    {
        $content = <<<'PHP'
            <?php declare(strict_types=1);
            
            namespace App\Tests\Fixtures;
            
            use App\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('/', methods: ['GET'])]
            final readonly class SelfCheckController
            {
                public function __invoke(): SuccessResponse
                {
                    return new SuccessResponse();
                }
            }
            
            PHP;

        $collector = new PhpParser()->parseContent($content);

        $this->assertEquals([
            new ClassDto(
                name: new NodeDto(45, 45, 'SelfCheckController'),
                attributes: [new AttributeDto(
                    name: new NodeDto(25, 25, Route::class),
                    args: [
                        0 => new ArgumentDto(
                            name: null,
                            value: new NodeDto(27, 27, '/'),
                        ),
                        1 => new ArgumentDto(
                            name: new NodeDto(30, 30, 'methods'),
                            value: new NodeDto(33, 35, ['GET']),
                        ),
                    ],
                )],
                methods: [
                    new MethodDto(
                        name: new NodeDto(53, 53, '__invoke'),
                        attributes: [],
                    ),
                ],
            )
        ], $collector->classes);
    }
}
