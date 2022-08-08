<?php

namespace App\Repository\Currency;

use App\Entity\Currency\Currency;
use App\Entity\Currency\CurrencyRate;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyRate>
 *
 * @method CurrencyRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyRate[]    findAll()
 * @method CurrencyRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    public function add(CurrencyRate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CurrencyRate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastByCurrency(Currency $currency, ?DateTimeInterface $date): ?CurrencyRate
    {
        $qb = $this->createQueryBuilder('rate')
            ->where('rate.currency = :currency')
            ->andWhere('rate.date < :date')
            ->setParameter('currency', $currency)
            ->setParameter('date', $date)
            ->orderBy('rate.date', Criteria::DESC)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
