<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Patient;
use App\Entity\Medecin;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Création d'un générateur de données faker
          //$faker = Faker\Factory::create('fr_FR');

          // créer patient

        $patient = new Patient();
        $patient->setNbRDVannule(0);
        $patient->setEmail("richard@user.com");
        $patient->setRoles(['ROLE_PATIENT']);
        $patient->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $patient->setNom("Richard");
        $patient->setPrenom("Boilley");
        $patient->setDateNaissance(new \dateTime());
        $patient->setTelephone("0619581248");
        $patient->setAdresse("1 rue Balangue");
        $patient->setComplementAdresse("Résidence du parc des sports Bât. A2");
        $patient->setCodePostal("64100");
        $patient->setVille("Bayonne");
        $patient->setSexe("Masculin");
        
        $manager->persist($patient);

        // créer Medecin
        $medecin= new Medecin();
        $medecin->setIdNational("HEnd8b2NelxiZ");
        $medecin->setEmail("kesly@user.com");
        $medecin->setRoles(['ROLE_MEDECIN']);
        $medecin->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $medecin->setNom("Kesly");
        $medecin->setPrenom("Gassant");
        $medecin->setDateNaissance(new \dateTime());
        $medecin->setTelephone("0638493640");
        $medecin->setAdresse("1 allée Paulmy");
        $medecin->setComplementAdresse("/");
        $medecin->setCodePostal("64200");
        $medecin->setVille("Anglet");
        $medecin->setSexe("Masculin");

        $manager->persist($medecin);



        $manager->flush();
    }
}
