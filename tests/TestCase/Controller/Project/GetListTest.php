<?php declare(strict_types=1);

namespace App\Tests\TestCase\Controller\Project;

use App\Controller\Project\GetListController;
use App\Tests\Infrastructure\ApiTestCase;

/** @see GetListController */
final class GetListTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->connectionPsql()->execute(<<<SQL
            TRUNCATE TABLE project RESTART IDENTITY CASCADE;
            INSERT INTO project (id, name) VALUES (1, 'maker-php')
            SQL
        );
    }

    public function test(): void
    {
        $this
            ->request('GET', '/api/project')
            ->expectedCode(200)
            ->expectedJsonContent(<<<JSON
                {
                  "items": [
                    {
                      "id": 1,
                      "name": "maker-php"
                    }
                  ]
                }
                JSON
            );
    }
}
