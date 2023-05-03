<?php

namespace App\Repository;

use App\Entity\EinkaufzuVerkaufPreis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EinkaufzuVerkaufPreis>
 *
 * @method EinkaufzuVerkaufPreis|null find($id, $lockMode = null, $lockVersion = null)
 * @method EinkaufzuVerkaufPreis|null findOneBy(array $criteria, array $orderBy = null)
 * @method EinkaufzuVerkaufPreis[]    findAll()
 * @method EinkaufzuVerkaufPreis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EinkaufzuVerkaufPreisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EinkaufzuVerkaufPreis::class);
    }

    public function save(EinkaufzuVerkaufPreis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EinkaufzuVerkaufPreis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EinkaufzuVerkaufPreis[] Returns an array of EinkaufzuVerkaufPreis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EinkaufzuVerkaufPreis
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
