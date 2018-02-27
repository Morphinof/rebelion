<?php

namespace Rebelion\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Rebelion\Entity\Characteristics;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CharacteristicsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Characteristics::class);
    }
}
