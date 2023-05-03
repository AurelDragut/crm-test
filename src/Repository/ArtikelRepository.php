<?php

namespace App\Repository;

use App\Entity\Artikel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Artikel>
 *
 * @method Artikel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artikel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artikel[]    findAll()
 * @method Artikel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtikelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artikel::class);
    }

    public function add(Artikel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Artikel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Artikel[] Returns an array of Artikel objects
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

//    public function findOneBySomeField($value): ?Artikel
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
        return $this->findBy([],['zeitstempel' => 'DESC'], 10, 0);
    }

    public function findTheLatest(): ?Artikel
    {
        return $this->findOneBy([],['zeitstempel' => 'DESC']);
    }
}
