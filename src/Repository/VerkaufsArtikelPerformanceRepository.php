<?php

namespace App\Repository;

use App\Entity\VerkaufsArtikelPerformance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VerkaufsArtikelPerformance>
 *
 * @method VerkaufsArtikelPerformance|null find($id, $lockMode = null, $lockVersion = null)
 * @method VerkaufsArtikelPerformance|null findOneBy(array $criteria, array $orderBy = null)
 * @method VerkaufsArtikelPerformance[]    findAll()
 * @method VerkaufsArtikelPerformance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerkaufsArtikelPerformanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VerkaufsArtikelPerformance::class);
    }

    public function save(VerkaufsArtikelPerformance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VerkaufsArtikelPerformance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VerkaufsArtikelPerformance[] Returns an array of VerkaufsArtikelPerformance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VerkaufsArtikelPerformance
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
