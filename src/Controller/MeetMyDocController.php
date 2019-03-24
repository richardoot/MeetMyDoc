<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Patient;
use App\Entity\Medecin;
use App\Entity\Creneau;
use App\Form\CreneauType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;


class MeetMyDocController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
      $medecin=  new Medecin();
      dump($this->getUser());

        return $this->render('meet_my_doc/rechercheMedecinAnonyme.html.twig', [
            'controller_name' => 'MeetMyDocController',
        ]);
    }


    /**
    *@Route("/patient/profil", name="meet_my_doc_patient_profil")
    */
    public function showProfilPatient(/*Patient $patient*/)
    {

      return $this->render('meet_my_doc/profilPatient.html.twig'/*, [
        'patient' => $patient
      ]*/);
    }


    /**
    *@Route("/medecin/profil", name="meet_my_doc_medecin_profil")
    */

    public function showProfilMedecin(/*Medecin $medecin*/)
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

        // definir l'interval
        $interval= new \DateInterval('PT30M');

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

      return $this->render('meet_my_doc_medecin_recapitulatifCreneau.html.twig');
    }


    /**
    *@Route("medecin/ConfirmerCreneau", name="meet_my_doc_confirmCreneau")
    */
    public function confirmCreneau()
    {
      // persister les Creneaux

      return $this->redirectToRoute('accueil');

    }

}
