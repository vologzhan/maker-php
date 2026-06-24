<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Directory;
use App\Enum\DirectoryType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Directory>
 */
class DirectoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Directory::class);
    }

    public function save(Directory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findById(int $directoryId): Directory
    {
        return $this
            ->createQueryBuilder('d')
            ->andWhere('d.id = :directoryId')
            ->setParameter('directoryId', $directoryId)
            ->getQuery()
            ->getSingleResult();
    }

    public function findByProjectId(int $projectId): Directory
    {
        return $this
            ->createQueryBuilder('d')
            ->andWhere('d.type = :type')
            ->andWhere('d.project = :projectId')
            ->setParameter('type', DirectoryType::Project)
            ->setParameter('projectId', $projectId)
            ->orderBy('d.path', 'ASC')
            ->getQuery()
            ->getSingleResult();
    }
}
