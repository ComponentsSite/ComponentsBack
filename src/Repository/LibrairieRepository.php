<?php

namespace App\Repository;

use App\Entity\Librairie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Librairie>
 *
 * @method Librairie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Librairie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Librairie[]    findAll()
 * @method Librairie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibrairieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Librairie::class);
    }

//    /**
//     * @return Librairie[] Returns an array of Librairie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Librairie
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
