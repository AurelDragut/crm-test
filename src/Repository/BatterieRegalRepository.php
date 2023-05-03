<?php

namespace App\Repository;

use App\Entity\BatterieRegal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BatterieRegal>
 *
 * @method BatterieRegal|null find($id, $lockMode = null, $lockVersion = null)
 * @method BatterieRegal|null findOneBy(array $criteria, array $orderBy = null)
 * @method BatterieRegal[]    findAll()
 * @method BatterieRegal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BatterieRegalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BatterieRegal::class);
    }

    public function save(BatterieRegal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BatterieRegal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BatterieRegal[] Returns an array of BatterieRegal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BatterieRegal
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
