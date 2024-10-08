<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 * 
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[] findAll()
 * @method Recipe[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)

 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @return Recipe[]
     */
    public function findWithDurationLowerThan(int $duration) : array
    {
        return $this->createQueryBuilder('r')
        ->where('r.duration < :duration')
        ->orderBy('r.duration', 'ASC')
        ->setParameter('duration', $duration)
        ->getQuery()
        ->getResult();
    }

    public function findTotalDuration() : int
    {
        return $this->createQueryBuilder('r')->select('SUM(r.duration) as total')->getQuery()->getSingleScalarResult();
    }
}
