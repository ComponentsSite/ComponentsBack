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
}
