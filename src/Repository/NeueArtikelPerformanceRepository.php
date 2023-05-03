<?php

namespace App\Repository;

use App\Entity\NeueArtikelPerformance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NeueArtikelPerformance>
 *
 * @method NeueArtikelPerformance|null find($id, $lockMode = null, $lockVersion = null)
 * @method NeueArtikelPerformance|null findOneBy(array $criteria, array $orderBy = null)
 * @method NeueArtikelPerformance[]    findAll()
 * @method NeueArtikelPerformance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NeueArtikelPerformanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NeueArtikelPerformance::class);
    }

    public function save(NeueArtikelPerformance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NeueArtikelPerformance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return NeueArtikelPerformance[] Returns an array of NeueArtikelPerformance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NeueArtikelPerformance
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
