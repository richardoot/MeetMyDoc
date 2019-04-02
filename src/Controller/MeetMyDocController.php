<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Repository\PatientRepository;
use App\Repository\MedecinRepository;
use App\Repository\CreneauRepository;

use App\Entity\Patient;
use App\Entity\Medecin;
use App\Entity\Creneau;

use App\Form\CreneauType;
use App\Form\ProfilPatientType;
use App\Form\ProfilMedecinType;
use App\Form\Medecin1Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;

class MeetMyDocController extends AbstractController
{
    /**
       *@Route("/", name="accueil")
       */
      public function index(MedecinRepository $repoMedecin,Request $request, ObjectManager $manager)
      {
        $medecin = new Medecin();
        //Création du Formulaire permettant de chercher un médecin
        $formulaireMedecin = $this->createForm(Medecin1Type::class, $medecin);

        $formulaireMedecin->handleRequest($request);

        if($formulaireMedecin->isSubmitted() /*&& $formulaireUser->isValid()*/){

            //Enregistrer les donnée en BD
              $nom = $medecin->getNom();
              $ville = $medecin->getVille();

              $medecins = $repoMedecin->findMedecinByForm($ville, $nom);
            //Redirection vers la page de connexion
              return $this->render('meet_my_doc/afficherLesMedecins.html.twig',["medecins" => $medecins]);
          }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireMedecin->createView();

      //Envoyer la page à la vue
      // Changer le nom de la vue renvoyée
        return $this->render('meet_my_doc/index.html.twig',["formulaire" => $vueFormulaire]);
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
     * @Route("/modifierPatient", name="app_modifier_patient")
     */
    public function modifierProfilPatient(Request $request, ObjectManager $manager)
    {
      $patient=$this->getUser();
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
      //Envoyer les données à la vue
        return $this->render('meet_my_doc/profilMedecin.html.twig');
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
        dump($data);
        $horaireDeb=$data->getHeureDebut();
        $horaireFin=$data->getHeureFin();
        $duree=$data->getDuree();
        //$horaireDeb=$data['heureDebut'];
        //$horaireFin=$data['heureFin'];
        //$duree=$data['duree'];

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

        while($tempsIn2 <= $horaireFin) // verifier que le deuxieme horaire (fin rendez vous ) est inferieur a l'horaire de fin
        {
          // creer le creneau
          $creneau= new Creneau();
          $creneau->setDateRDV($data->getDateRDV());
          $creneau->setHeureDebut($tempsIn1);
          $creneau->setHeureFin($tempsIn2);
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
     * @Route("/modifierMedecin", name="app_modifier_medecin")
     */
    public function modifierProfilMedecin(Request $request, ObjectManager $manager)
    {
      //Récupérer le médecin connecté
        $medecin = $this->getUser();

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


    /**
    *@Route("/medecin/afficherCreneau/semaine={debut}", name="meet_my_doc_medecin_afficher_creneau")
    */
    public function showCalendrierMedecin(CreneauRepository $repoCreneau,$debut)
    {
      //Récupérer le médecin connecter
        $medecin = $this->getUser();

      //Récupérer l'email du médecin
        $email = $medecin->getEmail();
      //Récupérer tous les créneaux du médecin connecter à partir de son email unique en BD
        $tousLesCreneaux = $repoCreneau->findCreneauxByMedecin($email);
        //$creneaux = $repoCreneau->findByMedecin(['id' => $medecin->getId()]);

      //Récupérer uniquement les créneaux demandé
          $fin = ($debut+1);
          //définir date du début de l'interval
            $intervalDebut = new \dateTime();
            $interval1= new \DateInterval('P' . $debut . 'W');
            $intervalDebut->add($interval1);
            $intervalDebut = $intervalDebut->format('Y-m-d');

          //definir date fin de l'interval
            $intervalFin = new \dateTime();
            $interval2= new \DateInterval('P' . $fin . 'W');
            $intervalFin->add($interval2);
            $intervalFin = $intervalFin->format('Y-m-d');

          //Enlever les créneaux expirés
            foreach ($tousLesCreneaux as $creneauCourant) {
              if($creneauCourant->getDateRDV()->format('Y-m-d') >= $intervalDebut && $creneauCourant->getDateRDV()->format('Y-m-d') <= $intervalFin){
                $creneaux[] = $creneauCourant;
              }
            }

      //Envoyer la page à la vue
        return $this->render('meet_my_doc/afficherCreneauxMedecin(Medecin).html.twig',["creneaux" => $creneaux, "semaineCourante" => $debut, "medecin" => $medecin]);
    }


    /**
    *@Route("/patient/afficherCreneauMedecin-{email}/semaine={debut}", name="meet_my_doc_patient_afficher_creneaux")
    */
    public function showCreneauxMedecin(MedecinRepository $repoMedecin, CreneauRepository $repoCreneau,$email,$debut)
    {
      //Récupérer tous les créneaux du médecin connecter à partir de son email unique en BD
        $tousLesCreneaux = $repoCreneau->findCreneauxByMedecin($email);

      //Récupérer le nom du médecins
        $leMedecin = $repoMedecin->findOneBy(['email' => $email]);

      //Récupérer uniquement les créneaux demandé
          $fin = ($debut+1);
          //définir date du début de l'interval
            $intervalDebut = new \dateTime();
            $interval1= new \DateInterval('P' . $debut . 'W');
            $intervalDebut->add($interval1);
            $intervalDebut = $intervalDebut->format('Y-m-d');

          //definir date fin de l'interval
            $intervalFin = new \dateTime();
            $interval2= new \DateInterval('P' . $fin . 'W');
            $intervalFin->add($interval2);
            $intervalFin = $intervalFin->format('Y-m-d');

          //Enlever les créneaux expirés
            foreach ($tousLesCreneaux as $creneauCourant) {
              if($creneauCourant->getDateRDV()->format('Y-m-d') >= $intervalDebut && $creneauCourant->getDateRDV()->format('Y-m-d') <= $intervalFin){
                $creneaux[] = $creneauCourant;
              }
            }

        //Envoyer les données à la vue
          return $this->render('meet_my_doc/afficherCreneauxMedecin(Patient).html.twig',["creneaux" => $creneaux, "semaineCourante" => $debut, "medecin" => $leMedecin]);
      }


      /**
      *@Route("/patient/prendreRDV-{id}", name="meet_my_doc_patient_prendre_rdv")
      */
      public function prendreRdv(MedecinRepository $repoMedecin, CreneauRepository $repoCreneau, ObjectManager $manager, $id=null)
      {
        //Récupérer le patient
          $patient = $this->getUser();

        //Récupérer le créneau
          $creneau_a_prendre = $repoCreneau->findOneBy(['id' => $id]);


        //Modifier le créneau
          //Changer état du créneau
            $creneau_a_prendre->setEtat('PRIS');

          //Définnir le patient qui a pris le créneau
            $creneau_a_prendre->setPatient($patient);


        //Enregistrer le créneau modifier en BD
          //Poser l'etiquette dessus
            $manager->persist($creneau_a_prendre);

          //Modifier le créneau en BD
            $manager->flush();


        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->render('meet_my_doc/afficherRecapitulatifRDV.html.twig',["creneau" => $creneau_a_prendre]);
      }

}
