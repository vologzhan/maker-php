<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Field;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Field>
 */
class FieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Field::class);
    }

    public function save(Field $field, bool $flush = false): void
    {
        $this->getEntityManager()->persist($field);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
