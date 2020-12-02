<?php

namespace App\Repository;

use App\Entity\Pay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pay|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pay|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pay[]    findAll()
 * @method Pay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pay::class);
    }

    // /**
    //  * @return Pay[] Returns an array of Pay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pay
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
