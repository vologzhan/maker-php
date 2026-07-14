<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Fs;

use App\Controller\Fs\GetTree;
use App\Tests\Infrastructure\ApiTestCase;

/**
 * @see GetTree
 */
final class GetTreeWithIndexTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this
            ->filesystem()
            ->deleteDir('/tmp/app')
            ->createFile('/tmp/app/project.maker');

        $this->connectionPsql()->execute(
            <<<SQL
            TRUNCATE TABLE directory RESTART IDENTITY CASCADE;
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('GET', '/api/fs')
            ->expectedCode(200)
            ->expectedJsonContent(
                <<<JSON
                {
                  "id": 1,
                  "name": "app",
                  "dirs": [],
                  "files": [
                    {
                      "id": 1,
                      "name": "project.maker"
                    }
                  ]
                }
                JSON
            );
    }
}
