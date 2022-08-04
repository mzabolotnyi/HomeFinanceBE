<?php

namespace App\Repository\Account;

use App\Entity\Account\ImportMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImportMethod>
 *
 * @method ImportMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImportMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImportMethod[]    findAll()
 * @method ImportMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportMethod::class);
    }

    public function add(ImportMethod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ImportMethod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
