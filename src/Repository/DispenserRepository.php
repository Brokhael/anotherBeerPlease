<?php

namespace App\Repository;

use App\Entity\Dispenser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dispenser>
 *
 * @method Dispenser|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dispenser|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dispenser[]    findAll()
 * @method Dispenser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispenserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dispenser::class);
    }

    public function save(Dispenser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Dispenser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @var float $flowVolume
     * @var float $price = null
     * 
     * Create a beer dispenser, if price was not set it will be set by the default value.
     */
    public function createBeerDispenser(float $flowVolume, float $price = null): Dispenser
    {
        $dispenser = new Dispenser();
        $dispenser->setFlowVolume($flowVolume);
        $dispenser->setStatus(Dispenser::STATUS_CLOSED);
        $dispenser->setTotalTimeOpen(0);
        $dispenser->setTotalMoney(0.0);
        $dispenser->setUsageCount(0);
        $dispenser->setLastOpenTime(null);
        $dispenser->setPrice($price ?? Dispenser::PRICE_DEFAULT); // if price is null is set to the default value
        $dispenser->setActive((null === $price || 0.0 === (float) $price || 0 > (float) $price) ? false : true); // if price is null the dispenser will not be active (beer is 0.0 but not free 😂)

        $entityManager = $this->getEntityManager();
        $entityManager->persist($dispenser);
        $entityManager->flush();

        return $dispenser;
    }

    /**
     * @var Dispenser  $dispenser
     * @var float|null $flowVolume
     * @var float|null $price
     * 
     * Update price or volume_flow to a dispenser, if price is null or is 0, the dispenser will not be active. 
     * If flow volume is 0 the dispenser can be active but it will not fill any glass and will not generate 💵
     */
    public function updateBeerDispenser(Dispenser $dispenser, $flowVolume, $price): Dispenser
    {
        $dispenser->setFlowVolume($flowVolume ?? $dispenser->getFlowVolume());
        $dispenser->setPrice($price ?? $dispenser->getPrice());
        $dispenser->setActive((null === $price || 0.0 === (float) $price) ? false : true);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($dispenser);
        $entityManager->flush();

        return $dispenser;
    }
    
    /**
     * @var Dispenser $dispenser
     * 
     * Open the dispenser
     */
    public function openBeerDispenser(Dispenser $dispenser): Dispenser
    {
        $dispenser->setStatus(Dispenser::STATUS_OPEN);
        $dispenser->setLastOpenTime(new \DateTime());
        $dispenser->setUsageCount($dispenser->getUsageCount() + 1);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($dispenser);
        $entityManager->flush();

        return $dispenser;
    }

    /**
     * @var Dispenser       $dispenser
     * @var float           $totalMoney
     * @var float           $totalTimeOpen
     * 
     * Close the dispenser, update time and money
     */
    public function closeBeerDispenser(Dispenser $dispenser, float $totalMoney, float $totalTimeOpen)
    {
        $dispenser->setStatus(Dispenser::STATUS_CLOSED);
        $dispenser->setTotalTimeOpen($totalTimeOpen);
        $dispenser->setTotalMoney($totalMoney);
        $dispenser->setLastOpenTime(null);
    
        $entityManager = $this->getEntityManager();
        $entityManager->persist($dispenser);
        $entityManager->flush();

        return $dispenser;
    }
}
