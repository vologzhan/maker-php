<?php declare(strict_types=1);

namespace App\Tests\TestCase\Service\Php;

use App\Dto\Php\ArgumentDto;
use App\Dto\Php\AttributeDto;
use App\Dto\Php\ClassDto;
use App\Dto\Php\MethodDto;
use App\Dto\Php\NodeDto;
use App\Dto\Php\ParamDto;
use App\Service\Php\PhpParser;
use App\Tests\Infrastructure\ApiTestCase;
use Symfony\Component\Routing\Attribute\Route;

final class PhpParserTest extends ApiTestCase
{
    public function test(): void
    {
        $content = <<<'PHP'
            <?php declare(strict_types=1);
            
            namespace Fixture\Controller;
            
            use Fixture\Request\EmptyRequest;
            use Fixture\Response\SuccessResponse;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('/', methods: ['GET'])]
            final readonly class SelfCheckController
            {
                public function __invoke(EmptyRequest $request): SuccessResponse
                {
                }
            }
            
            PHP;

        $collector = new PhpParser()->parseContent($content);

        $this->assertEquals([
            new ClassDto(
                fqn: 'Fixture\Controller\SelfCheckController',
                name: new NodeDto(50, 50, 'SelfCheckController'),
                attributes: [new AttributeDto(
                    name: new NodeDto(30, 30, Route::class),
                    args: [
                        0 => new ArgumentDto(
                            name: null,
                            value: new NodeDto(32, 32, '/'),
                        ),
                        1 => new ArgumentDto(
                            name: new NodeDto(35, 35, 'methods'),
                            value: new NodeDto(38, 40, ['GET']),
                        ),
                    ],
                )],
                methods: [
                    new MethodDto(
                        name: new NodeDto(58, 58, '__invoke'),
                        return: new NodeDto(66, 66, 'Fixture\Response\SuccessResponse'),
                        params: [
                            new ParamDto(
                                type: new NodeDto(60, 60, 'Fixture\Request\EmptyRequest'),
                                name: new NodeDto(62, 62, 'request'),
                                comment: null,
                                nullable: false,
                                annotationVar: null,
                            )
                        ],
                        attributes: [],
                    ),
                ],
            )
        ], $collector->classes);
    }
}
