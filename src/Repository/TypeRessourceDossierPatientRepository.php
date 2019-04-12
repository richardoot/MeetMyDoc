<?php

namespace App\Repository;

use App\Entity\TypeRessourceDossierPatient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeRessourceDossierPatient|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeRessourceDossierPatient|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeRessourceDossierPatient[]    findAll()
 * @method TypeRessourceDossierPatient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRessourceDossierPatientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeRessourceDossierPatient::class);
    }

    // /**
    //  * @return TypeRessourceDossierPatient[] Returns an array of TypeRessourceDossierPatient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeRessourceDossierPatient
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
