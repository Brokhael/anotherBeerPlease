<?php

namespace App\Repository;

use App\Entity\Revenue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Revenue>
 *
 * @method Revenue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Revenue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Revenue[]    findAll()
 * @method Revenue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RevenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Revenue::class);
    }

    public function createDispenserRevenue(int $dispenser, float $money, float $time): void
    {
        $revenue = new Revenue();
        $revenue->setDispenserId($dispenser);
        $revenue->setServiceTime($time);
        $revenue->setServiceMoney($money);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($revenue);
        $entityManager->flush();
    }
}
