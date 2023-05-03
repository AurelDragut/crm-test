<?php

namespace App\Repository;

use App\Entity\Auftrag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Auftrag>
 *
 * @method Auftrag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auftrag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auftrag[]    findAll()
 * @method Auftrag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuftragRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auftrag::class);
    }

    public function add(Auftrag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Auftrag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Auftrag[] Returns an array of Auftrag objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Auftrag
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findLatest(): array
    {
        return $this->findBy([],null, 10, 0);
    }
}
