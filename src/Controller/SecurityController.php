<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function inscription(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
      //Création d'un utilisateur vide qui sera rempli par le Formulaire
        $user = new User();

      //Création du Formulaire permettant de saisir un utilisateur
      $formulaireUser = $this->createForm(UserType::class, $user);


      //Analyse la derniére requete html pour voir si le tableau post
      // contient les variables qui ont été rentrées, si c'est le cas
      // alors il hydrate l'objet user
        $formulaireUser->handleRequest($request);

        //dump($entreprise);
      //Vérifier que le formulaire a été soumis
    if($formulaireUser->isSubmitted() /*&& $formulaireUser->isValid()*/){
            //Entrer le role et la date de naissance de l'utilisateur
              $user->setSexe("Masculin"); //Temporaire
              $user->setDateNaissance(new \dateTime()); //Temporaire
              $user->setRoles(['ROLE_PATIENT']); //Temporaire

            //Encoder le mot de passe
              $encoded = $encoder->encodePassword($user, $user->getPassword());
              $user->setPassword($encoded);

            //Enregistrer les donnée en BD
              $manager->persist($user);
              $manager->flush();

            //Redirection vers la page de connexion
              return $this->redirectToRoute('app_login');
        }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireUser->createView();

      //Envoyer la page à la vue
        return $this->render('security/inscription.html.twig',["formulaire" => $vueFormulaire,"action" => "ajout"]);
    }
}
