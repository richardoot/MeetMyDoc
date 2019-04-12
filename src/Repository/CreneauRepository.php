<?php

namespace App\Repository;

use App\Entity\Creneau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Creneau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Creneau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Creneau[]    findAll()
 * @method Creneau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreneauRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Creneau::class);
    }

     /**
      * @return Creneau[] Returns an array of Creneau objects
      */

    public function findCreneauxByMedecin2($email) //non optimisé
    {
        return $this->createQueryBuilder('c')
            ->join('c.medecin', 'm')
            ->andWhere('m.id = :id')
            ->setParameter('id', $id)
            //->orderBy('c.date', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Creneau[] Returns an array of Creneau objects
     */
     public function findCreneauxByMedecin($id) //Non optimisé
     {
         $query = $this->getEntityManager()->createQuery(
           "SELECT c, m, s
            FROM App\Entity\Creneau c
            JOIN c.medecin m
            JOIN m.specialite s
            WHERE m.id = :id
            ORDER BY c.dateRDV, c.heureDebut");


         $query->setParameter('id', $id);
         $users = $query->getResult();
         return $users;
     }



    /**
     * @return Creneau[] Returns an array of Creneau objects
     */

   public function findCreneauxByPatient2($id) //Non optimisé
   {
       return $this->createQueryBuilder('c')
           ->join('c.patient', 'p')
           ->andWhere('p.id = :id')
           ->setParameter('id', $id)
           //->orderBy('c.date', 'ASC')
           //->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }


   /**
    * @return Creneau[] Returns an array of Creneau objects
    */
    public function findCreneauxByPatient($id)
    {
        $query = $this->getEntityManager()->createQuery(
          "SELECT c, p
           FROM App\Entity\Creneau c
           JOIN c.patient p
           WHERE p.id = :id
           ORDER BY c.dateRDV, c.heureDebut");


        $query->setParameter('id', $id);
        $users = $query->getResult();
        return $users;
    }

    /**
    * @return Creneau[] Returns an array of Creneau objects
    */
    public function findRDVetPatientByMedecin($id)
    {
        $query = $this->getEntityManager()->createQuery(
          "SELECT c, m
           FROM App\Entity\Creneau c
           JOIN c.medecin m
           WHERE m.id = :id
           AND c.etat = 'PRIS'
           ORDER BY c.dateRDV, c.heureDebut");


        $query->setParameter('id', $id);
        $users = $query->getResult();
        return $users;
    }

    /*
    public function findOneBySomeField($value): ?Creneau
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

/*
    public funtion findBySemaineQB($semaine)
    {
      return $this->createQueryBuilder('c')
                  ->where('c.horaireDebut= :semaine')
                  ->andWhere(c.horaireFin)
                  ->setParameter('semaine', $semaine)
                  ->getQuery()
                  ->getResult()
      ;
    }
*/

/*
    public function findBySemaineDQL($semaine)
    {

      // recuperer les gestionnaire d'entité

      $entityManger->$this->getEntityManager();

      // construction de la requete
      $requete= $entityManger->createQuery(
     'SELECT c
      FROM App\Entity\Creneau c
      WHERE c.horaireDebut= :'

      );

      // definir les valaeur injecté

      //execuer la requete
    }

    */


/*
    public function findBySemaineDQL1(String $critere)
    {

      // recuperer les gestionnaire d'entité

      $entityManager=$this->getEntityManager();
      $critereRecherche= explode("classementpar", $critere)[1];
      dump($critereRecherche);
    /*  $cre=$this->findby( [], [$critereRecherche=>'ASC']);
      *

      // construction de la requete
      $requete= $entityManager->createQuery(
     'SELECT c
      FROM App\Entity\Creneau c
      ORDER BY c.'.$critereRecherche.' ASC'

      );

      // definir les valaeur injecté
      //$requete->setParameter('$critereRecherche', $critereRecherche);
      //execuer la requete

      $requete->execute();

      //return $cre;
    }
    */
}
