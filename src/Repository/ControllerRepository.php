<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Controller;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Controller>
 */
class ControllerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Controller::class);
    }

    public function save(Controller $entity): Controller
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }

    public function delete(Controller $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findById(int $id): Controller
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
