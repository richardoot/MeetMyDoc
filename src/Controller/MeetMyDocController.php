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
<<<<<<< HEAD
use App\Entity\Creneau;
use App\Form\CreneauType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\CreneauRepository;
=======

use App\Form\ProfilPatientType;
use App\Form\ProfilMedecinType;

>>>>>>> 602a2439f0afeae106051e14fa3396db25e315ab

class MeetMyDocController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(CreneauRepository $repo)
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
    *@Route("/medecin/ajouterCreneau", name="meet_my_doc_medecin_ajouter_creneau")
    */
    public function addCreneau(Request $request, ObjectManager $manager)
    {

      $formulaireCreneau = $this->createForm(CreneauType::class);

      $formulaireCreneau->handleRequest($request);

      if($formulaireCreneau->isSubmitted() && $formulaireCreneau->isValid())
      {
        // Recuperer les données saisie par l'utilisateur
        $data=$formulaireCreneau->getData();
        //dd($data);
        $horaireDeb=$data['horaireDebut'];
        $horaireFin=$data['horaireFin'];
        $duree=$data['duree'];

        // definir l'interval des creneau à partir du duree entré par l'utilisateur
        $interval= new \DateInterval('PT'.$duree.'M');
        //dd($interval);
        // initialiser le tempsIntermediaire à l'horaire de debut + duree pou la création de 1 creneau
        $tempsIn1= clone $horaireDeb; // premier horaire (debut rendez vous)
        //dd($tempsIn1);
        $tempsIn2= clone $horaireDeb; // deuxieme horaire (fin rendez vous), INITIALISE
        $tempsIn2->add($interval); //deuxieme horaire (fin rendez vous)
        //dd($tempsIn2);
        //dd($tempsIn2<$horaireFin);
        while($tempsIn2<=$horaireFin) // verifier que le deuxieme horaire (fin rendez vous ) est inferieur a l'horaire de fin
        {
          // creer le creneau
          $creneau= new Creneau();
          $creneau->setHoraireDebut($tempsIn1);
          $creneau->setHoraireFin($tempsIn2);
          $creneau->setDuree($duree);
          $creneau->setMedecin($this->getUser());
          $creneau->setEtat('NON PRISE');

          $manager->persist($creneau);
          $manager->flush();

          // MAJ les $tempsIn1 et $tempsIn2
          $tempsIn1->add($interval);
          $tempsIn2->add($interval);
          //dd($creneau);


        }

        return $this->redirectToRoute('accueil');
      }

      return $this->render('meet_my_doc/medecinAjouterCreneau.html.twig', ['vueFormulaire'=>$formulaireCreneau->createView()]);

    }

    /**
    *@Route("/medecin/recapitulatifCreneau", name="meet_my_doc_recapitulatif_ajouter_creneau")
    */

    public function showRecapAjouterCreneau()
    {

      return $this->render('meet_my_doc/medecinRecapitulatifCreneau.html.twig');
    }


    /**
    *@Route("medecin/ConfirmerCreneau", name="meet_my_doc_confirmCreneau")
    */
    public function confirmCreneau()
    {
      // persister les Creneaux

      return $this->redirectToRoute('accueil');

    }

    /**
    *@Route("/patient/afficherCreneau{}", name="meet_my_doc_afficher_creneaux_patient")
    */
    public function showCreneauPatient(CreneauRepository $repoCreneau)
    {

      $creneau->$repoCreneau=findAll();

      return $this->render('meet_my_doc/patientAffciherCreneaux', ['creneaux'=> $creneaux]);

    }

    /*
    *@Route("/medecin/afficherCreneau", name="meet_my_doc_afficher_creneaux_medecin")
    */
    public function showCreneauMedecin(CreneauRepository $repoCreneau)
    {
        //$creneaux= $repoCreneau->findOneBy(['medecin'=>$this->getUser()]);

        return $this->render('meet_my_doc/medecinAfficherCreneaux');
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
