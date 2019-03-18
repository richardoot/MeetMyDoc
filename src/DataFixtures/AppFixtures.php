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
        $medecin1->setIdNational("fghjhgfdfghjhgfezerty");

        $user1 = new User();
        $user1->setEmail("richard@user.com");
        $user1->setRoles(['ROLE_PATIENT']);
        $user1->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $user1->setNom("Richard");
        $user1->setPrenom("Boilley");
        $user1->setDateNaissance(new \dateTime());
        $user1->setSexe("Masculin");
        ///-------
        $user1->setPatient($patient1);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail("kesly@user.com");
        $user2->setRoles(['ROLE_MEDECIN']);
        $user2->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $user2->setNom("Kesly");
        $user2->setPrenom("Gassant");
        $user2->setDateNaissance(new \dateTime());
        $user2->setSexe("Masculin");
        //---------------
        $user2->setMedecin($medecin1);
        $manager->persist($user2);
        $manager->flush();
    }
}
