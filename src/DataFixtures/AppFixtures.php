<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Création d'un générateur de données faker
          //$faker = Faker\Factory::create('fr_FR');

        $user1 = new User();
        $user1->setEmail("richard@user.com");
        $user1->setRoles(['ROLE_PATIENT']);
        $user1->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $user1->setNom("Boilley");
        $user1->setPrenom("Richard");
        $user1->setDateNaissance(new \dateTime());
        $user1->setSexe("Masculin");

        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail("kesly@user.com");
        $user2->setRoles(['ROLE_MEDECIN']);
        $user2->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $user2->setNom("Gassant");
        $user2->setPrenom("Kesly");
        $user2->setDateNaissance(new \dateTime());
        $user2->setSexe("Masculin");

        $manager->persist($user2);
        $manager->flush();
    }
}
