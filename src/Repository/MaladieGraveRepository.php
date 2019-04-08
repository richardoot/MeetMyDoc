<?php

namespace App\Repository;

use App\Entity\MaladieGrave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MaladieGrave|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaladieGrave|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaladieGrave[]    findAll()
 * @method MaladieGrave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaladieGraveRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MaladieGrave::class);
    }

    // /**
    //  * @return MaladieGrave[] Returns an array of MaladieGrave objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaladieGrave
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
