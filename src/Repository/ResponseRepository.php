<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Response>
 */
class ResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }

    public function save(Response $entity): Response
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }

    public function findById(int $id): Response
    {
        return $this
            ->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
