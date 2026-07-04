<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Dir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            ->createQueryBuilder('d')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
