<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use App\Entity\User;
use App\Entity\Patient;
use App\Entity\Medecin;
use App\Entity\Specialite;
use App\Entity\DossierPatient;
//use App\Form\UserType;
use App\Form\PatientType;
use App\Form\ProfilPatientType;
use App\Form\MedecinType;
use App\Form\UserPatientType;
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
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

    }


    /**
     * @Route("/inscription/Medecin", name="app_inscriptionMedecin")
     */
    public function inscriptionMedecin(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
      //Création d'un utilisateur vide qui sera rempli par le Formulaire
        $medecin = new Medecin();

      //Création du Formulaire permettant de saisir un utilisateur
      $formulaireUser = $this->createForm(MedecinType::class, $medecin);


      //Analyse la derniére requete html pour voir si le tableau post
      // contient les variables qui ont été rentrées, si c'est le cas
      // alors il hydrate l'objet user
        $formulaireUser->handleRequest($request);

      //Vérifier que le formulaire a été soumis
      if($formulaireUser->isSubmitted() && $formulaireUser->isValid()){
            //Entrer le role et la date de naissance de l'utilisateur
              $medecin->setSexe("Masculin"); //Temporaire
              $medecin->setDateNaissance(new \dateTime()); //Temporaire
              $medecin->setRoles(['ROLE_MEDECIN']); //Temporaire

            //Encoder le mot de passe
              $encoded = $encoder->encodePassword($medecin, $medecin->getPassword());
              $medecin->setPassword($encoded);

            //Enregistrer les donnée en BD
              $manager->persist($medecin);
              $manager->flush();

            //Redirection vers la page de connexion
              return $this->redirectToRoute('app_login');
      }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireUser->createView();

      //Envoyer la page à la vue
        return $this->render('security/inscriptionMedecin.html.twig',["formulaire" => $vueFormulaire,"action" => "ajout"]);
    }


    /**
     * @Route("/inscription/Patient", name="app_inscriptionPatient")
     */
    public function inscriptionPatient(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
      //Création d'un utilisateur vide qui sera rempli par le Formulaire
        $patient = new Patient();

      //Création du Formulaire permettant de saisir un utilisateur
        $formulaireUser = $this->createForm(PatientType::class, $patient);


      //Analyse la derniére requete html pour voir si le tableau post
      // contient les variables qui ont été rentrées, si c'est le cas
      // alors il hydrate l'objet user
        $formulaireUser->handleRequest($request);

        //dump($entreprise);
      //Vérifier que le formulaire a été soumis
        if($formulaireUser->isSubmitted() && $formulaireUser->isValid()){
            //Créer un dossier patient vide
              $dossierPatient = new DossierPatient();

            //Entrer le role et la date de naissance de l'utilisateur
              $patient->setSexe("Masculin"); //Temporaire
              $patient->setDateNaissance(new \dateTime()); //Temporaire
              $patient->setRoles(['ROLE_PATIENT']); //Temporaire
              $patient->setNbRDVannule(0);

            //Encoder le mot de passe
              $encoded = $encoder->encodePassword($patient, $patient->getPassword());
              $patient->setPassword($encoded);

            //Définir le patient du dossierPatient
              $dossierPatient->setPatient($patient);


            //Enregistrer les donnée en BD
              $manager->persist($dossierPatient);
              $manager->persist($patient);
              $manager->flush();

            //Redirection vers la page de connexion
              return $this->redirectToRoute('app_login');
        }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireUser->createView();

      //Envoyer la page à la vue
        return $this->render('security/inscriptionPatient.html.twig',["formulaire" => $vueFormulaire,"action" => "ajout"]);
    }
}
