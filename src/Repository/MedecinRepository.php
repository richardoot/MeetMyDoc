<?php

namespace App\Repository;

use App\Entity\Medecin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Medecin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medecin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medecin[]    findAll()
 * @method Medecin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedecinRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Medecin::class);
    }

     /**
      * @return Medecin[] Returns an array of Medecin objects
      */
    
    public function findMedecinByForm($ville,$nom)
    {
        $query = $this->getEntityManager()->createQuery("SELECT m FROM App\Entity\Medecin m WHERE m.ville LIKE :ville AND m.nom LIKE :nom OR m.prenom LIKE :nom ORDER BY m.ville, m.nom");
        $query->setParameter('ville', '%'.$ville.'%');
        $query->setParameter('nom', '%'.$nom.'%');
        $users = $query->getResult();
        return $users;
    }
    

    /*
    public function findOneBySomeField($value): ?Medecin
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
