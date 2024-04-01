<?php

namespace App\Repository;

use App\Entity\Variables;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Variables>
 *
 * @method Variables|null find($id, $lockMode = null, $lockVersion = null)
 * @method Variables|null findOneBy(array $criteria, array $orderBy = null)
 * @method Variables[]    findAll()
 * @method Variables[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariablesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Variables::class);
    }
}
