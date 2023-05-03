<?php

namespace App\Repository;

use App\Entity\PotenzialBasisArtikel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PotenzialBasisArtikel>
 *
 * @method PotenzialBasisArtikel|null find($id, $lockMode = null, $lockVersion = null)
 * @method PotenzialBasisArtikel|null findOneBy(array $criteria, array $orderBy = null)
 * @method PotenzialBasisArtikel[]    findAll()
 * @method PotenzialBasisArtikel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PotenzialBasisArtikelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PotenzialBasisArtikel::class);
    }

    public function save(PotenzialBasisArtikel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PotenzialBasisArtikel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PotenzialBasisArtikel[] Returns an array of PotenzialBasisArtikel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PotenzialBasisArtikel
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
