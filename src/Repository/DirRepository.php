<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Dir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dir>
 */
class DirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dir::class);
    }

    public function save(Dir $dir): Dir
    {
        $this->getEntityManager()->persist($dir);
        return $dir;
    }

    public function findById(int $id): Dir
    {
        return $this
            ->createQb()
            ->andWhere('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @return Dir[]
     */
    public function findRoots(): array
    {
        return $this
            ->createQb()
            ->andWhere('d.parent IS NULL')
            ->getQuery()
            ->getResult();
    }

    private function createQb(string $alias = 'd'): QueryBuilder
    {
        return $this->createQueryBuilder($alias);
    }
}
