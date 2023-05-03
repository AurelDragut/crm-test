<?php

namespace App\Repository;

use App\Entity\TopBasisArtikelNullBestand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TopBasisArtikelNullBestand>
 *
 * @method TopBasisArtikelNullBestand|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopBasisArtikelNullBestand|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopBasisArtikelNullBestand[]    findAll()
 * @method TopBasisArtikelNullBestand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopBasisArtikelNullBestandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopBasisArtikelNullBestand::class);
    }

    public function save(TopBasisArtikelNullBestand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TopBasisArtikelNullBestand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TopBasisArtikelNullBestand[] Returns an array of TopBasisArtikelNullBestand objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TopBasisArtikelNullBestand
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
