<?php

namespace App\Repository;

use App\Entity\Category;
// use App\DTO\CategoryWithCountDTO;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    // public function findAllWithCountDQL(): array
    // {
    //     return $this->getEntityManager()->createQuery(<<<DQL
    //         SELECT c.id, COUNT(c.id), c.name 
    //         FROM App\Entity\Category c
    //         LEFT JOIN c.recipes r
    //         GROUP BY c.id
    //     DQL)->getResult();
    // }

    // /**
    //  * @return App\\DTO\\CategoryWithCountDTO[]
    //  */
    public function findAllWithCount(): array
    {
        return $this->createQueryBuilder('c')
            ->select('NEW App\\DTO\\CategoryWithCountDTO(c.id, c.name, COUNT(c.id))')
            // ->select('c as category')
            ->leftJoin('c.recipes', 'r')
            ->groupBy('c.id')    
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
