<?php

namespace App\Repository;

use App\Entity\RessourceDossierPatient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RessourceDossierPatient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RessourceDossierPatient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RessourceDossierPatient[]    findAll()
 * @method RessourceDossierPatient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourceDossierPatientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RessourceDossierPatient::class);
    }

    // /**
    //  * @return RessourceDossierPatient[] Returns an array of RessourceDossierPatient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RessourceDossierPatient
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
