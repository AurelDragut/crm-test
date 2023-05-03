<?php

namespace App\Repository;

use App\Entity\AuftragStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuftragStatus>
 *
 * @method AuftragStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuftragStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuftragStatus[]    findAll()
 * @method AuftragStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuftragStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuftragStatus::class);
    }

    public function add(AuftragStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AuftragStatus $entity, bool $flush = false): void
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
        return $this->findBy([],null, 15, 0);
    }

    public function findtheLatest(): array
    {
        return $this->findBy([],['datum' => 'DESC'], 1, 0);
    }

    public function findtheLatestfromAuftrag($auftragId): ?AuftragStatus
    {
        return $this->findOneBy(['Auftrag' => $auftragId],['Datum' => 'DESC'], 1, 0);
    }

}
