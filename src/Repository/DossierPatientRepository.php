<?php

namespace App\Repository;

use App\Entity\DossierPatient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DossierPatient|null find($id, $lockMode = null, $lockVersion = null)
 * @method DossierPatient|null findOneBy(array $criteria, array $orderBy = null)
 * @method DossierPatient[]    findAll()
 * @method DossierPatient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossierPatientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DossierPatient::class);
    }

    // /**
    //  * @return DossierPatient[] Returns an array of DossierPatient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DossierPatient
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
