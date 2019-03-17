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

          $user = new User();

          $user->setEmail("richard@user.com");
          $user->setRoles(['ROLE_PATIENT']);
          $user->setPassword('$2y$10$qaHa4SF6o1ECaZoc6.xCluHRnlImOPwReLffIjagZhQzM8s59Lk7i');
          $user->setNom("Richard");
          $user->setPrenom("Boilley");
          //$date = new DateTime();
          $user->setDateNaissance(new \dateTime());
          $user->setSexe("Masculin");

        $manager->persist($user);
        $manager->flush();
    }
}
