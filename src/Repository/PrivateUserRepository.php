<?php

namespace App\Repository;

use App\Entity\PrivateUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrivateUser>
 *
 * @method PrivateUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateUser[]    findAll()
 * @method PrivateUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivateUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrivateUser::class);
    }

//    /**
//     * @return PrivateUser[] Returns an array of PrivateUser objects
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

//    public function findOneBySomeField($value): ?PrivateUser
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
