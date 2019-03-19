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

        $patient1 = new Patient();
        $patient1->setNbRDVannule(0);

        // créer Medecin
        $medecin1= new Medecin();
        $medecin1->setIdNational("HEnd8b2NelxiZ");

        $user1 = new User();
        $user1->setEmail("richard@user.com");
        $user1->setRoles(['ROLE_PATIENT']);
        $user1->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $user1->setNom("Richard");
        $user1->setPrenom("Boilley");
        $user1->setDateNaissance(new \dateTime());
        $user1->setTelephone("0619581248");
        $user1->setAdresse("1 rue Balangue");
        $user1->setComplementAdresse("Résidence du parc des sports Bât. A2");
        $user1->setCodePostal("64100");
        $user1->setVille("Bayonne");

        $user1->setSexe("Masculin");
        ///-------
        //$user1->setPatient($patient1);
        $patient1->setUser($user1);
        $manager->persist($user1);
        //$manager->persist($patient1);

        $user2 = new User();
        $user2->setEmail("kesly@user.com");
        $user2->setRoles(['ROLE_MEDECIN']);
        $user2->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $user2->setNom("Kesly");
        $user2->setPrenom("Gassant");
        $user2->setDateNaissance(new \dateTime());
        $user2->setTelephone("0638493640");
        $user2->setAdresse("1 allée Paulmy");
        $user2->setComplementAdresse("/");
        $user2->setCodePostal("64200");
        $user2->setVille("Anglet");
        $user2->setSexe("Masculin");

        //---------------
        //$user2->setMedecin($medecin1);
        $medecin1->setUser($user2);
        $manager->persist($user2);
        //$manager->persist($medecin1);



        $manager->flush();
    }
}
