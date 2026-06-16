<?php

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

    public function save(Controller $controller, bool $flush = false): void
    {
        $this->getEntityManager()->persist($controller);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
