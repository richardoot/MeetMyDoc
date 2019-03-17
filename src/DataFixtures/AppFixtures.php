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
        $user1->setNom("Richard");
        $user1->setPrenom("Boilley");
        $user1->setDateNaissance(new \dateTime());
        $user1->setSexe("Masculin");

        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail("richard@user.com");
        $user2->setRoles(['ROLE_PATIENT']);
        $user2->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i'); //Password = User
        $user2->setNom("Richard");
        $user2->setPrenom("Boilley");
        $user2->setDateNaissance(new \dateTime());
        $user2->setSexe("Masculin");
        $manager->persist($user2);
        $manager->flush();
    }
}
