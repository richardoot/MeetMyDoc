<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Patient;
use App\Entity\Medecin;


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
    *@Route("/patient/profil{id}", nome=meet_my_doc_patient_profil)
    */
    public function showProfilPatient(Patient $patient)
    {

      return $this->render('meet_my_doc/profilPatient.html.twig', [
        'patient' => $patient
      ]);
    }


    /**
    *@Route("/medecin/profil{id}", nome=meet_my_doc_medecin_profil)
    */
<<<<<<< HEAD
=======

>>>>>>> relation-patient-user
    public function showProfilMedecin(Medecin $medecin)
    {

      return $this->render('meet_my_doc/profilMedecin.html.twig', [
        'medecin' => $medecin
      ]);
    }


}
