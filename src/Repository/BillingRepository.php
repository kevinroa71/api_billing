<?php

namespace App\Repository;

use App\Entity\Billing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BillingRepository extends ServiceEntityRepository
{
    /**
     * Construction function
     *
     * @param ManagerRegistry $registry Service ManagerRegistry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Billing::class);
    }
}
