<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    /**
     * @return Animal[] Returns an array of Animal objects
     */
    public function findSearchAnimal($value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.name LIKE :val')
            ->orWhere('a.description LIKE :val')
            ->setParameter('val', "%$value%")
            ->getQuery()
            ->getResult();
    }
}
