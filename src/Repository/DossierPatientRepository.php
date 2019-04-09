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

     /**
      * @return DossierPatient[] Returns an array of DossierPatient objects
      */

    public function findByEmailMedecin($email)
    {
      $query = $this->getEntityManager()->createQuery(
        "SELECT d, m
         FROM App\Entity\DossierPatient d
         JOIN d.medecins m
         JOIN m.specialite s
         WHERE m.email = :email
         ORDER BY c.dateRDV, c.heureDebut");


      $query->setParameter('email', $email);
      $users = $query->getResult();
      return $users;
    }


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
