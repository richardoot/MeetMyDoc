<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Repository\PatientRepository;
use App\Repository\MedecinRepository;

use App\Entity\Patient;
use App\Entity\Medecin;

use App\Form\ProfilPatientType;
use App\Form\ProfilMedecinType;


class MeetMyDocController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('meet_my_doc/rechercheMedecinAnonyme.html.twig', [
            'controller_name' => 'MeetMyDocController',
        ]);
    }


    /**
    *@Route("/patient/profil", name="meet_my_doc_patient_profil")
    */
    public function showProfilPatient(PatientRepository $repositoryPatient)
    {


      return $this->render('meet_my_doc/profilPatient.html.twig'/*, [
        'patient' => $patient
      ]*/);
    }

    /**
     * @Route("/modifierPatient-{email}", name="app_modifier_patient")
     */
    public function modifierProfilPatient(Request $request, ObjectManager $manager, Patient $patient)
    {
      //Création du Formulaire permettant de saisir un patient
        $formulaireUser = $this->createForm(ProfilPatientType::class, $patient);


      //Analyse la derniére requete html pour voir si le tableau post
      // contient les variables qui ont été rentrées, si c'est le cas
      // alors il hydrate l'objet user
        $formulaireUser->handleRequest($request);

        //dump($entreprise);
      //Vérifier que le formulaire a été soumis
        if($formulaireUser->isSubmitted() /*&& $formulaireUser->isValid()*/){

            //Enregistrer les donnée en BD
              $manager->persist($patient);
              $manager->flush();

            //Redirection vers la page de connexion
              return $this->redirectToRoute('meet_my_doc_patient_profil');
          }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireUser->createView();

      //Envoyer la page à la vue
        return $this->render('meet_my_doc/modifierProfilPatient.html.twig',["formulaire" => $vueFormulaire]);
    }




    //-----------------------PARTIE MEDECIN-----------------------//


    /**
    *@Route("/medecin/profil", name="meet_my_doc_medecin_profil")
    */

    public function showProfilMedecin(MedecinRepository $repositoryMedecin)
    {

      return $this->render('meet_my_doc/profilMedecin.html.twig'/*, [
        'medecin' => $medecin
      ]*/);
    }


    /**
     * @Route("/modifierMedecin-{email}", name="app_modifier_medecin")
     */
    public function modifierProfilMedecin(Request $request, ObjectManager $manager, Medecin $medecin)
    {
      //Création du Formulaire permettant de saisir un patient
        $formulaireUser = $this->createForm(ProfilMedecinType::class, $medecin);


      //Analyse la derniére requete html pour voir si le tableau post
      // contient les variables qui ont été rentrées, si c'est le cas
      // alors il hydrate l'objet user
        $formulaireUser->handleRequest($request);

        //dump($entreprise);
      //Vérifier que le formulaire a été soumis
        if($formulaireUser->isSubmitted() /*&& $formulaireUser->isValid()*/){

            //Enregistrer les donnée en BD
              $manager->persist($medecin);
              $manager->flush();

            //Redirection vers la page de connexion
              return $this->redirectToRoute('meet_my_doc_medecin_profil');
          }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireUser->createView();

      //Envoyer la page à la vue
        return $this->render('meet_my_doc/modifierProfilMedecin.html.twig',["formulaire" => $vueFormulaire]);
    }


}
