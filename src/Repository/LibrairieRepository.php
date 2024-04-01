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
}
