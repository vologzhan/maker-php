<?php declare(strict_types=1);

namespace App\Tests\TestCase\Service\Php;

use App\Dto\Php\ArgumentDto;
use App\Dto\Php\AttributeDto;
use App\Dto\Php\ClassDto;
use App\Dto\Php\ImportDto;
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
            use Fixture\Response\SuccessResponse AS Response;
            use Symfony\Component\Routing\Attribute\Route;
            
            #[Route('/', methods: ['GET'])]
            final readonly class SelfCheckController
            {
                public function __invoke(EmptyRequest $request): Response
                {
                }
            }
            
            PHP;

        $collector = new PhpParser()->parseContent($content);

        $this->assertEquals([
            new ClassDto(
                fqn: 'Fixture\Controller\SelfCheckController',
                name: new NodeDto(54, 54, 'SelfCheckController'),
                attributes: [new AttributeDto(
                    name: new NodeDto(34, 34, Route::class),
                    args: [
                        0 => new ArgumentDto(
                            name: null,
                            value: new NodeDto(36, 36, '/'),
                        ),
                        1 => new ArgumentDto(
                            name: new NodeDto(39, 39, 'methods'),
                            value: new NodeDto(42, 44, ['GET']),
                        ),
                    ],
                )],
                methods: [
                    new MethodDto(
                        name: new NodeDto(62, 62, '__invoke'),
                        return: new NodeDto(70, 70, 'Fixture\Response\SuccessResponse'),
                        params: [
                            new ParamDto(
                                type: new NodeDto(64, 64, 'Fixture\Request\EmptyRequest'),
                                name: new NodeDto(66, 66, 'request'),
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

        $this->assertEquals(14, $collector->importsPos);
        $this->assertEquals(31, $collector->importsEnd);

        $this->assertEquals([
            new ImportDto(
                name: new NodeDto(16,16,'Fixture\Request\EmptyRequest'),
                alias: null,
            ),
            new ImportDto(
                name: new NodeDto(21,21,'Fixture\Response\SuccessResponse'),
                alias: new NodeDto(25,25,'Response'),
            ),
            new ImportDto(
                name: new NodeDto(30,30,'Symfony\Component\Routing\Attribute\Route'),
                alias: null,
            ),
        ], $collector->imports);
    }
}
