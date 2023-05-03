<?php

namespace App\Repository;

use App\Entity\BasisArtikel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BasisArtikel>
 *
 * @method BasisArtikel|null find($id, $lockMode = null, $lockVersion = null)
 * @method BasisArtikel|null findOneBy(array $criteria, array $orderBy = null)
 * @method BasisArtikel[]    findAll()
 * @method BasisArtikel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasisArtikelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasisArtikel::class);
    }

    public function add(BasisArtikel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BasisArtikel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BasisArtikel[] Returns an array of BasisArtikel objects
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

//    public function findOneBySomeField($value): ?BasisArtikel
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
