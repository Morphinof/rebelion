<?php

namespace Rebelion\Repository;

use Rebelion\Entity\Effect\Effect;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class EffectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Effect::class);
    }

    /*
    public function searchByClass($instance)
    {
        $em = $this->getEntityManager();

        return $this->createQueryBuilder('e')
            ->where($em->getExpressionBuilder()->like('e.reference', ':reference'))
            ->setParameter('reference', "%$reference%")
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
