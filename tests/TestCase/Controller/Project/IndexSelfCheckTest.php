<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Project;

use App\Tests\Infrastructure\Annotation\Skip;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see IndexController
 */
final class IndexSelfCheckTest extends ApiTestCase
{
    #[Skip]
    public function test(): void
    {
        $this
            ->request('POST', '/api/project/index', body: <<<JSON
                {
                  "path": "/app"
                }
                JSON
            )
            ->expectedCode(200);
    }
}
