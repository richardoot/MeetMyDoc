<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilder;

use App\Repository\PatientRepository;
use App\Repository\MedecinRepository;
use App\Repository\CreneauRepository;
use App\Repository\DossierPatientRepository;
use App\Repository\AdminRepository;
use App\Repository\RessourceDossierPatientRepository;
use App\Repository\TypeRessourceDossierPatientRepository;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use App\Entity\Admin;
use App\Entity\Patient;
use App\Entity\Medecin;
use App\Entity\Creneau;
use App\Entity\Specialite;
use App\Entity\DossierPatient;
use App\Entity\TypeRessourceDossierPatient;
use App\Entity\RessourceDossierPatient;

use App\Form\RessourceDossierPatientType;
use App\Form\CreneauType;
use App\Form\Creneau2Type;
use App\Form\ProfilPatientType;
use App\Form\ProfilMedecinType;
use App\Form\Medecin1Type;
use App\Form\SupprimerCreneauType;
use App\Form\DossierPatientType;
use App\Form\ModifDossierPatientParMedecinType;

use Symfony\Component\Form\Extension\Core\Type\DateType;

class MeetMyDocController extends AbstractController
{

  //------------------------ TOUS LES UTILISATEURS ------------------------//
      /**
       *@Route("/", name="accueil")
       */
      public function index(MedecinRepository $repoMedecin,Request $request, ObjectManager $manager)
      {
        if($this->isGranted('ROLE_MEDECIN') )
        {
          return $this->RedirectToRoute('meet_my_doc_medecin_ajouter_creneau');
        }

        $medecin = new Medecin();
        //Création du Formulaire permettant de chercher un médecin
        $formulaireMedecin = $this->createForm(Medecin1Type::class, $medecin);

        $formulaireMedecin->handleRequest($request);

        if($formulaireMedecin->isSubmitted()) {

            //Enregistrer les donnée en BD
              $nom = $medecin->getNom();
              $ville = $medecin->getVille();
              $specialite = $medecin->getSpecialite();

              $patient = $this->getUser();

              $medecins = $repoMedecin->findMedecinByForm($ville, $nom, $specialite);

              dump($medecins);

              if($patient == NULL) {
                return $this->render('meet_my_doc/patient/afficherLesMedecinsAnonyme.html.twig',['medecins' => $medecins]);
              } else return $this->render('meet_my_doc/patient/afficherLesMedecins.html.twig',['medecins' => $medecins, 'patient' => $patient]);
          }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireMedecin->createView();

      //Envoyer la page à la vue
      // Changer le nom de la vue renvoyée
        return $this->render('meet_my_doc/index.html.twig',["formulaire" => $vueFormulaire]);
      }


      /**
      * @Route("/inscription", name="meet_my_doc_inscription")
      */
      public function inscription()
      {
        return $this->render('meet_my_doc/choixInscription.html.twig');
      }


      // Ajoute la spécialité généraliste en BD
      /**
      * @Route("/initSpecialite", name="meet_my_doc_init_specialite")
      */
      public function initSpecialite(Request $request, ObjectManager $manager)
      {
        $specialite = new Specialite();

        $specialite->setNom('Généraliste');

        $specialite->__construct();

        $manager->persist($specialite);

        $manager->flush();

        return $this->RedirectToRoute('accueil');
      }



  //------------------------ PATIENT ------------------------//

    /**
    *@Route("/patient/profil", name="meet_my_doc_patient_profil")
    */
    public function showProfilPatient(PatientRepository $repositoryPatient)
    {


      return $this->render('meet_my_doc/patient/profilPatient.html.twig');
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
        return $this->render('meet_my_doc/patient/modifierProfilPatient.html.twig',["formulaire" => $vueFormulaire]);
    }



    /**
    *@Route("/patient/afficherCreneauMedecin/{id}/semaine={debut}", name="meet_my_doc_patient_afficher_creneaux")
    */
    public function showCreneauxMedecin(MedecinRepository $repoMedecin, CreneauRepository $repoCreneau,$id,$debut)
    {
      //Récupérer tous les créneaux du médecin connecter à partir de son email unique en BD
        $tousLesCreneaux = $repoCreneau->findCreneauxByMedecin($id);

      //Récupérer le nom du médecins
        $leMedecin = $repoMedecin->findOneBy(['id' => $id]);

        dump($leMedecin->getId());
      //Récupérer uniquement les créneaux demandé
          $fin = ($debut+1);
          //définir date du début de l'interval
            $intervalDebut = new \dateTime();
            $interval1= new \DateInterval('P' . $debut . 'W');
            $intervalDebut->add($interval1);
            $intervalDebut->add(new \DateInterval('PT' . '2' . 'H'));
            $intervalDebutString = $intervalDebut->format('Y-m-d');

          //definir date fin de l'interval
            $intervalFin = new \dateTime();
            $interval2= new \DateInterval('P' . $fin . 'W');
            $intervalFin->add($interval2);
            $intervalFinString = $intervalFin->format('Y-m-d');

            // Initialiser les créneaux à vide pour éviter un problème au moment de passer la variable à la vue
            $creneaux=[];

            //Enlever les créneaux expirés
            foreach ($tousLesCreneaux as $creneauCourant) {
              if($creneauCourant->getDateRDV()->format('Y-m-d') >= $intervalDebutString && $creneauCourant->getDateRDV()->format('Y-m-d') <= $intervalFinString && $creneauCourant->getHeureDebut() >= $intervalDebut->format('H-i')){
                $creneaux[] = $creneauCourant;
              }
            }

            //Définir tableau
              $tabRef = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
              $tab = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];


              //initialiser
                $joursRef = [];
                $jours = [];

              //Vérifier que le tableau de créneaux n'est pas vide
              if($creneaux != []){
                //Trouver la place du jour courant
                  for($i=0 ; $i < sizeof($tab) ; $i++){
                    if($creneaux[0]->getDateRDV()->format('D') == $tabRef[$i]){
                      $place = $i;
                    }
                  }

                //Réordonner le tableau
                  $case = $place;
                  for($k=0 ; $k <= sizeof($tab) ; $k++){

                    if($case == 7){
                      $case = -1;
                    }

                    if($case != -1){
                      $jours[] = $tab[$case];
                      $joursRef[] = $tabRef[$case];
                    }

                    if($case == ($place-1)){
                      break;
                    }
                    $case++;
                  }
              }

              if(sizeof($joursRef) == 0){
                $this->addFlash('pas-de-creneau', 'Aucun créneau prévu pour cette semaine!');
              }

        //Envoyer les données à la vue
          return $this->render('meet_my_doc/patient/afficherCreneauxMedecin(Patient).html.twig',["creneaux" => $creneaux, "semaineCourante" => $debut, "medecin" => $leMedecin, "jours" => $jours, "joursRef" => $joursRef]);
      }


      /**
      *@Route("/patient/prendreRDV-{id}", name="meet_my_doc_patient_prendre_rdv")
      */
      public function prendreRdv(MedecinRepository $repoMedecin, CreneauRepository $repoCreneau, ObjectManager $manager,Request $request, $id=null)
      {

        //Récupérer le patient
            $patient = $this->getUser();

        //Récupérer le créneau
            $creneau_a_prendre = $repoCreneau->findOneBy(['id' => $id]);

            $formulaire = $this->createForm(Creneau2Type::class, $creneau_a_prendre);

            $medecin = $creneau_a_prendre->getMedecin();

            $medecin->addPatient($patient);
        //Modifier le créneau
          //Changer état du créneau
            $creneau_a_prendre->setEtat('PRIS');

          //Définnir le patient qui a pris le créneau
            $creneau_a_prendre->setPatient($patient);

            $formulaire->handleRequest($request);

            if($formulaire->isSubmitted()){

              //Enregistrer le créneau en BD
              $manager->persist($creneau_a_prendre);

              //Modifier le créneau en BD
              $manager->flush(); 
  
              //Redirection vers la page de connexion
              return $this->redirectToRoute('meet_my_doc_patient_afficher_rdv');
            }

        


        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->render('meet_my_doc/patient/afficherRecapitulatifRDV.html.twig',["creneau" => $creneau_a_prendre,'vueFormulaire'=>$formulaire->createView()]);
      }


      /**
      *@Route("/patient/afficherRDV", name="meet_my_doc_patient_afficher_rdv")
      */
      public function afficherLesRDV(CreneauRepository $repoCreneau)
      {
        //Récupérer le patient actuellement connecté
          $patient = $this->getUser();

        //Récupérer le mail du patient actuellement connecté
          $id = $patient->getId();

        //Récupérer les créneaux prix par le patient
          $rdv = $repoCreneau->findCreneauxByPatient($id);

        //Récupérer la date d'aujourd'hui
          $dateAJD = new \dateTime();

        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->render('meet_my_doc/patient/afficherLesRDV.html.twig',["creneaux" => $rdv, "dateAJD" => $dateAJD, "patient" => $patient]);
      }



      /**
      *@Route("/patient/annulerRDV-{id}", name="meet_my_doc_patient_annuler_rdv")
      */
      public function annulerRdv(MedecinRepository $repoMedecin, CreneauRepository $repoCreneau, ObjectManager $manager, $id=null)
      {
        //Récupérer le créneau à supprimer
          $creneau_a_annuler = $repoCreneau->findOneBy(['id' => $id]);

        //Modifier le créneau
          //Changer état du créneau
            $creneau_a_annuler->setEtat('NON PRIS');

          //Définnir le patient qui a pris le créneau
            $creneau_a_annuler->setPatient(NULL);

            $creneau_a_annuler->setMotif(NULL);

        //Enregistrer le créneau annuler en BD
          //Poser l'etiquette dessus
            $manager->persist($creneau_a_annuler);

          //Modifier le créneau en BD
            $manager->flush();

        //Récupérer le mail du patient actuellement connecté
          $email = $this->getUser()->getEmail();

        //Récupérer les créneaux prix par le patient
          $rdv = $repoCreneau->findCreneauxByPatient($email);

        //Récupérer la date d'aujourd'hui
          $dateAJD = new \dateTime();


        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->redirectToRoute('meet_my_doc_patient_afficher_rdv');
      }


      /**
      *@Route("/patient/modifierRDV-{id}", name="meet_my_doc_patient_modifier_rdv")
      */
      public function modifierRdv(MedecinRepository $repoMedecin, CreneauRepository $repoCreneau, ObjectManager $manager, $id=null)
      {
        //-----------------SUPPRESSION DU RDV -----------------//
          //Récupérer le créneau à modifier
            $creneau_a_modifier = $repoCreneau->findOneBy(['id' => $id]);

          //Modifier le créneau
            //Changer état du créneau
              $creneau_a_modifier->setEtat('NON PRIS');

            //Définnir le patient qui a pris le créneau
              $creneau_a_modifier->setPatient(NULL);


          //Enregistrer le créneau annuler en BD
            //Poser l'etiquette dessus
              $manager->persist($creneau_a_modifier);

            //Modifier le créneau en BD
              $manager->flush();


        //---------PRESENTATION DU CALENDRIER DU MEDECIN---------//
        //Récupérer le médecin
          $leMedecin = $creneau_a_modifier->getMedecin();

        //Récupérer tous les créneaux du médecin connecter à partir de son email unique en BD
          $tousLesCreneaux = $repoCreneau->findCreneauxByMedecin($leMedecin->getEmail());

        //Récupérer uniquement les créneaux demandé
            $debut = 0;
            $fin = ($debut+1);
            //définir date du début de l'interval
              $intervalDebut = new \dateTime();
              $interval1= new \DateInterval('P' . $debut . 'W');
              $intervalDebut->add($interval1);
              $intervalDebut->add(new \DateInterval('PT' . '2' . 'H'));
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


            //Définir tableau
              $tabRef = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
              $tab = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];


              //initialiser
                $joursRef = [];
                $jours = [];

              //Vérifier que le tableau de créneaux n'est pas vide
              if($creneaux != []){
                //Trouver la place du jour courant
                  for($i=0 ; $i < sizeof($tab) ; $i++){
                    if($creneaux[0]->getDateRDV()->format('D') == $tabRef[$i]){
                      $place = $i;
                    }
                  }

                //Réordonner le tableau
                  $case = $place;
                  for($k=0 ; $k <= sizeof($tab) ; $k++){

                    if($case == 7){
                      $case = -1;
                    }

                    if($case != -1){
                      $jours[] = $tab[$case];
                      $joursRef[] = $tabRef[$case];
                    }

                    if($case == ($place-1)){
                      break;
                    }
                    $case++;
                  }
              }



        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->render('meet_my_doc/patient/afficherCreneauxMedecin(Patient).html.twig',["creneaux" => $creneaux, "semaineCourante" => $debut, "medecin" => $leMedecin, "joursRef" => $joursRef]);
      }


      /**
      *@Route("/afficherProfil/medecin-{id}", name="meet_my_doc_patient_afficher_profil_medecin")
      */
      public function afficherProfilMedecinAuPatient(MedecinRepository $repoMedecin,$id)
      {
        //Récupérer le mail du patient actuellement connecté
          $medecin = $repoMedecin->findOneBy(['id' => $id]);

          dump($medecin);
        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->render('meet_my_doc/patient/afficherProfilMedecin(Patient).html.twig',["medecin" => $medecin]);
      }


      /**
      *@Route("/patient/dossier", name="meet_my_doc_patient_afficher_dossier")
      */
      public function afficherDossier(DossierPatientRepository $repoDossierPatient)
      {
        //Récupérer le patient connecté actuellement
          $patient = $this->getUser();

        //Récupérer le dossier patient
          $dossier = $repoDossierPatient->findOneBy(['patient' => $patient]);

          $ressources = $dossier->getRessourcesDossierPatient();

        //Renvoyer les donées à la vue
          return $this->render('meet_my_doc/patient/dossierPatient.html.twig',['patient' => $patient, "dossierPatient" => $dossier,"ressources" => $ressources]);
      }


      /**
       * @Route("/patient/modifierDossier", name="meet_my_doc_modifier_dossier_patient")
       */
      public function modifierDossierPatient(Request $request, ObjectManager $manager, DossierPatientRepository $repoDossierPatient)
      {
        //Récupérer le patient actuelle
          $patient=$this->getUser();

        //Récupérer son dossier patient
          $dossierPatient = $repoDossierPatient->findOneBy(['patient' => $patient]);

        //Création du Formulaire permettant de saisir un patient
          $formulaireDossier = $this->createForm(DossierPatientType::class, $dossierPatient);


        //Analyse la derniére requete html pour voir si le tableau post
        // contient les variables qui ont été rentrées, si c'est le cas
        // alors il hydrate l'objet user
          $formulaireDossier->handleRequest($request);

          //dump($entreprise);
        //Vérifier que le formulaire a été soumis
          if($formulaireDossier->isSubmitted() /*&& $formulaireDossier->isValid()*/){

              //Enregistrer les donnée en BD
                $manager->persist($dossierPatient);
                $manager->flush();

              //Redirection vers la page de connexion
                return $this->redirectToRoute('meet_my_doc_patient_afficher_dossier');
            }

        //Générer la représentation graphique du formulaire
          $vueFormulaire = $formulaireDossier->createView();

        //Envoyer la page à la vue
          return $this->render('meet_my_doc/patient/modifierDossierPatient.html.twig',["formulaire" => $vueFormulaire]);
      }


      /**
      *@Route("/patient/partagerDossier/medecin-{id}", name="meet_my_doc_patient_partager_dossier")
      */
      public function partagerDossier(MedecinRepository $repoMedecin, DossierPatientRepository $repoDossierPatient, ObjectManager $manager, $id)
      {
        //Récupérer le patient connecté actuellement
          $patient = $this->getUser();

        //Récupérer le dossier patient
          $dossier = $repoDossierPatient->findOneBy(['patient' => $patient]);

        //Récupérer le medecin à qui partager le dossier
          $medecin = $repoMedecin->findOneBy(['id' => $id]);

          if($medecin->isGranted($dossier))
          {
            $this->addFlash('error','Vous avez déjà partagé votre dossier patient avec le médecin '.$medecin->getNom().' '.$medecin->getPrenom());
            return $this->redirectToRoute('meet_my_doc_afficher_medecin_favoris');
          } else
            {
              //Donnée accées au médecin
              $dossier->addMedecin($medecin);
              $manager->persist($dossier);
              $manager->flush();
              $this->addFlash('success-partage', 'Dossier patient correctement partagé avec le docteur '.$medecin->getNom().' '.$medecin->getPrenom());
              return $this->redirectToRoute('meet_my_doc_afficher_medecin_favoris');
            }
      }


    //----------------------------- MEDECIN -----------------------------//


    /**
    *@Route("/medecin/profil", name="meet_my_doc_medecin_profil")
    */

    public function showProfilMedecin(MedecinRepository $repositoryMedecin)
    {
      //Envoyer les données à la vue
        return $this->render('meet_my_doc/medecin/profilMedecin.html.twig');
    }

    /**
    *@Route("/medecin/ajouterCreneau", name="meet_my_doc_medecin_ajouter_creneau")
    */
    public function addCreneaux(Request $request, ObjectManager $manager, CreneauRepository $repoCreneau)
    {
      //Récupérer le médecin courant
        $medecin = $this->getUser();

      $formulaireCreneau = $this->createForm(CreneauType::class);

      $formulaireCreneau->handleRequest($request);

      if($formulaireCreneau->isSubmitted() && $formulaireCreneau->isValid())
      {
        // Recuperer les données saisie par l'utilisateur
          $data=$formulaireCreneau->getData();
          dump($data);
          $horaireDeb=$data->getHeureDebut();
          $horaireFin=$data->getHeureFin();
          // $duree=$data->getDuree();
          $duree = 30;
        // definir l'interval des creneau à partir du duree entré par l'utilisateur
          $interval= new \DateInterval('PT'.$duree.'M');

        // initialiser le tempsIntermediaire à l'horaire de debut + duree pou la création de 1 creneau
          $tempsIn1= clone $horaireDeb; // premier horaire (debut rendez vous)
          $tempsIn2= clone $horaireDeb; // deuxieme horaire (fin rendez vous), INITIALISE
          $tempsIn2->add($interval); //deuxieme horaire (fin rendez vous)


        while($tempsIn2 <= $horaireFin) // verifier que le deuxieme horaire (fin rendez vous ) est inferieur a l'horaire de fin
        {
          //Commencer à creer le creneau
            $creneau= new Creneau();
            $creneau->setDateRDV($data->getDateRDV()); //toujours la même date
            $creneau->setHeureDebut($tempsIn1);
            $creneau->setHeureFin($tempsIn2);

          //Récupérer s'il existe un créneaux pour ce médecin avec une date et heure de début identique à celle souhaitant être ajouté en BD
            $leCreneauxEnBD = $repoCreneau->findOneBy(['dateRDV' => $creneau->getDateRDV(), 'heureDebut' => $creneau->getHeureDebut(), 'medecin' => $medecin]);
            dump($leCreneauxEnBD);

          //Ajouter le créneau en BD uniquement s'il n'existe pas
          if($leCreneauxEnBD == null){
            $creneau->setDuree($duree);
            $creneau->setMedecin($this->getUser());
            $creneau->setEtat('NON PRIS');
            $creneau->setMotif(NULL);
            $manager->persist($creneau);
            $manager->flush();
          }

          // MAJ les $tempsIn1 et $tempsIn2
            $tempsIn1->add($interval);
            $tempsIn2->add($interval);
        }

        $this->addFlash('success', 'Créneau(x) ajouté(s) correctement !');

        return $this->redirectToRoute('meet_my_doc_medecin_afficher_creneau',["debut"=>0,"duree" =>$duree]);
      }

      return $this->render('meet_my_doc/medecin/medecinAjouterCreneau.html.twig', ['vueFormulaire'=>$formulaireCreneau->createView()]);

    }




    /**
    *@Route("/medecin/supprimerCreneaux", name="meet_my_doc_medecin_supprimer_creneaux")
    */
    public function removeCreneaux(Request $request, ObjectManager $manager, CreneauRepository $repoCreneau) //permet de supprimer plusieur creneau"X"
    {
      //Récupérer le médecin courant
        $medecin = $this->getUser();

      $formulaireCreneau = $this->createForm(SupprimerCreneauType::class);

      $formulaireCreneau->handleRequest($request);

      if($formulaireCreneau->isSubmitted() && $formulaireCreneau->isValid())
      {
        // Recuperer les données saisie par l'utilisateur
          $data=$formulaireCreneau->getData();
          dump($data);
          $horaireDeb=$data->getHeureDebut();
          $horaireFin=$data->getHeureFin();
          $duree=30;

        // definir l'interval des creneau à partir du duree entré par l'utilisateur
          $interval= new \DateInterval('PT'.$duree.'M');

        // initialiser le tempsIntermediaire à l'horaire de debut + duree pou la création de 1 creneau
          $tempsIn1= clone $horaireDeb; // premier horaire (debut rendez vous)
          $tempsIn2= clone $horaireDeb; // deuxieme horaire (fin rendez vous), INITIALISE
          $tempsIn2->add($interval); //deuxieme horaire (fin rendez vous)

        while($tempsIn2 <= $horaireFin) // verifier que le deuxieme horaire (fin rendez vous ) est inferieur a l'horaire de fin
        {
          //Commencer à creer le creneau
            $creneau= new Creneau();
            $creneau->setDateRDV($data->getDateRDV()); //toujours la même date
            $creneau->setHeureDebut($tempsIn1);
            $creneau->setHeureFin($tempsIn2);

          //Récupérer s'il existe un créneaux avec une date et heure de début identique à celle souhaitant être ajouté en BD
            $leCreneauxEnBD = $repoCreneau->findOneBy(['dateRDV' => $creneau->getDateRDV(), 'heureDebut' => $creneau->getHeureDebut(), 'medecin' => $medecin]);
            dump($leCreneauxEnBD);

          //Supprimer le créneau en BD uniquement s'il existe
          if($leCreneauxEnBD != null ){

            $manager->remove($leCreneauxEnBD);
            $manager->flush();
          }
          // MAJ les $tempsIn1 et $tempsIn2
          $tempsIn1->add($interval);
          $tempsIn2->add($interval);
        }

        $this->addFlash('success', 'Créneau(x) supprimé(s) correctement !');

        return $this->redirectToRoute('meet_my_doc_medecin_afficher_creneau',['debut' => 0]);
      }

      return $this->render('meet_my_doc/medecin/medecinSupprimerCreneau.html.twig', ['vueFormulaire'=>$formulaireCreneau->createView()]);

    }



    /**
     * @Route("/medecin/modifierMedecin", name="app_modifier_medecin")
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
        return $this->render('meet_my_doc/medecin/modifierProfilMedecin.html.twig',["formulaire" => $vueFormulaire]);


      //Envoyer la page à la vue
      return $this->RedirectToRoute('meet_my_doc_patient_afficher_creneaux',["creneaux" => $creneaux, "semaineCourante" => $debut,"semaineCourante" => $debut, "id" => $medecin->getId(), "medecin"=> $medecin, "joursRef" => $joursRef,"debut" => $debut,]);


    }


    /**
    *@Route("/medecin/afficherCreneau/semaine={debut}", name="meet_my_doc_medecin_afficher_creneau")
    */
    public function showCalendrierMedecin(CreneauRepository $repoCreneau,$debut)
    {
      //Récupérer le médecin connecter
        $medecin = $this->getUser();

      //Récupérer l'email du médecin
        $id = $medecin->getId();
      //Récupérer tous les créneaux du médecin connecter à partir de son email unique en BD
        $tousLesCreneaux = $repoCreneau->findCreneauxByMedecin($id);
        //$creneaux = $repoCreneau->findByMedecin(['id' => $medecin->getId()]);
        if($debut < 0){
          $debut = 0;
          $this->addFlash('fail','Vous avez été redirigé vers le calendrier de la semaine courant car vous avez essayé de consulté des dates passées');
          return $this->RedirectToRoute('meet_my_doc_medecin_afficher_creneau',['debut' => $debut]);
        }
      //Récupérer uniquement les créneaux demandé
          $fin = ($debut+1);
          //définir date du début de l'interval
            $intervalDebut = new \dateTime();
            $interval1= new \DateInterval('P' . $debut . 'W');
            $intervalDebut->add($interval1);
            $intervalDebut->add(new \DateInterval('PT' . '2' . 'H'));
            $intervalDebut = $intervalDebut->format('Y-m-d');

          //definir date fin de l'interval
            $intervalFin = new \dateTime();
            $interval2= new \DateInterval('P' . $fin . 'W');
            $intervalFin->add($interval2);
            $intervalFin = $intervalFin->format('Y-m-d');

          //Enlever les créneaux expirés
            $creneaux = [];
            foreach ($tousLesCreneaux as $creneauCourant) {
              if($creneauCourant->getDateRDV()->format('Y-m-d') >= $intervalDebut && $creneauCourant->getDateRDV()->format('Y-m-d') <= $intervalFin){
                $creneaux[] = $creneauCourant;
              }
            }

            //Définir tableau
              $tabRef = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
              $tab = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];


              //initialiser
                $joursRef = [];
                $jours = [];

              //Vérifier que le tableau de créneaux n'est pas vide
              if($creneaux != []){
                //Trouver la place du jour courant
                  for($i=0 ; $i < sizeof($tab) ; $i++){
                    if($creneaux[0]->getDateRDV()->format('D') == $tabRef[$i]){
                      $place = $i;
                    }
                  }

                //Réordonner le tableau
                  $case = $place;
                  for($k=0 ; $k <= sizeof($tab) ; $k++){

                    if($case == 7){
                      $case = -1;
                    }

                    if($case != -1){
                      $jours[] = $tab[$case];
                      $joursRef[] = $tabRef[$case];
                    }

                    if($case == ($place-1)){
                      break;
                    }
                    $case++;
                  }
              }

              if(sizeof($joursRef) == 0){
                $this->addFlash('pas-de-creneau', 'Aucun créneau prévu pour cette semaine!');
              }

        $duree = 30;
      //Envoyer la page à la vue
        return $this->render('meet_my_doc/medecin/afficherCreneauxMedecin(Medecin).html.twig',["creneaux" => $creneaux, "semaineCourante" => $debut, "medecin" => $medecin, "joursRef" => $joursRef, "duree" => $duree]);
    }



    /**
    *@Route("/medecin/supprimerCreneau-{id}", name="meet_my_doc_medecin_supprimer_creneau")
    */
    public function supprimerUnCreneauDuCalendrierMedecin(ObjectManager $manager, CreneauRepository $repoCreneau,$id=null)
    {
      //SUPPRIMER LE CRENEAU CHOISIE PAR LE MEDECIN
        //SI LE CRENEAU EST PRIS FAUT NOTIFIER LE PATIENT

        //SUPPRIMER LE CRENEAU
          //Récupérer le creneau à supprimer
            $creneau_a_supprimer = $repoCreneau->findOneBy(['id' => $id]);
            //dump($creneau_a_supprimer);
          //Posser une étiquette sur le creneau
            $manager->remove($creneau_a_supprimer);

          //Supprimer le creneau de la BD
            $manager->flush();


      //REDIRIGER VERS LE CALENDRIER QUI AFFICHE LES CRENEAUX DU MEDECIN
        //Récupérer le médecin connecter
          $medecin = $this->getUser();

        //Récupérer l'email du médecin
          $id = $medecin->getId();

        //Récupérer tous les créneaux du médecin connecter à partir de son email unique en BD
          $tousLesCreneaux = $repoCreneau->findCreneauxByMedecin($id);

        //Récupérer uniquement les créneaux demandé
            $debut = 0;
            $fin = ($debut+1);
            //définir date du début de l'interval
              $intervalDebut = new \dateTime();
              $interval1= new \DateInterval('P' . $debut . 'W');
              $intervalDebut->add($interval1);
              $intervalDebut->add(new \DateInterval('PT' . '2' . 'H'));
              $intervalDebut = $intervalDebut->format('Y-m-d');

            //definir date fin de l'interval
              $intervalFin = new \dateTime();
              $interval2= new \DateInterval('P' . $fin . 'W');
              $intervalFin->add($interval2);
              $intervalFin = $intervalFin->format('Y-m-d');

            //Enlever les créneaux expirés
              $creneaux = [];
              foreach ($tousLesCreneaux as $creneauCourant) {
                if($creneauCourant->getDateRDV()->format('Y-m-d') >= $intervalDebut && $creneauCourant->getDateRDV()->format('Y-m-d') <= $intervalFin){
                  $creneaux[] = $creneauCourant;
                }
              }


            //Définir tableau
              $tabRef = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
              $tab = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];


              //initialiser
                $joursRef = [];
                $jours = [];

              //Vérifier que le tableau de créneaux n'est pas vide
              if($creneaux != []){
                //Trouver la place du jour courant
                  for($i=0 ; $i < sizeof($tab) ; $i++){
                    if($creneaux[0]->getDateRDV()->format('D') == $tabRef[$i]){
                      $place = $i;
                    }
                  }

                //Réordonner le tableau
                  $case = $place;
                  for($k=0 ; $k <= sizeof($tab) ; $k++){

                    if($case == 7){
                      $case = -1;
                    }

                    if($case != -1){
                      $jours[] = $tab[$case];
                      $joursRef[] = $tabRef[$case];
                    }

                    if($case == ($place-1)){
                      break;
                    }
                    $case++;
                  }
              }



      //Envoyer la page à la vue
        return $this->redirectToRoute('meet_my_doc_medecin_afficher_creneau',["debut"=>0]);
        }


      /**
      *@Route("/medecin/afficherProfil/patient-{id}", name="meet_my_doc_medecin_afficher_profil_patient")
      */
      public function afficherProfilPatientAuMedecin(PatientRepository $repoPatient,$id)
      {
        //Récupérer le mail du patient actuellement connecté
          $patient = $repoPatient->findOneBy(['id' => $id]);

        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->render('meet_my_doc/medecin/afficherProfilPatient(Medecin).html.twig',["patient" => $patient]);
      }


//      /**
//      *@Route("/medecin/mes-patients2", name="meet_my_doc_mes_patients2")
//      */
      /*public function rechercherMesPatients(PatientRepository $repoPatient, ObjectManager $manager)
      {
        //-----------------SUPPRESSION DU RDV -----------------//
            $medecin = $this->getUser();
          //Récupérer le créneau à modifier
            $patients = $repoPatient->findMesPatients($medecin);

        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->render('meet_my_doc/medecin/afficherMesPatients.html.twig',["patients" => $patients]);
      }*/


      /**
      * @Route("/medecin/mes-patients", name="meet_my_doc_mes_patients")
      */
      public function afficherPatientParTab()
      {
        $medecin = $this->getUser();

        $patients = $medecin->getPatients();

        return $this->Render('meet_my_doc/medecin/afficherPatientsParTab.html.twig',["patients" => $patients]);
      }


      /**
      * @Route("/medecin/afficher-dossier/{id}", name="meet_my_doc_dossier_de_mes_patients")
      */
      public function afficherDossierDuPatient(DossierPatientRepository $repoDossierPatient ,PatientRepository $repoPatient,$id)
      {
        //Récupérer le patient et le medecin
          $medecin = $this->getUser();
          $patient = $repoPatient->findOneById($id);
        //Récupérer le dossier patient
          $dossierP = $repoDossierPatient->findOneByPatient($patient);

          $ressources = $dossierP->getRessourcesDossierPatient();
        //Vérifier que le médecin à un droit d'access
          $droit = $medecin->isGranted($dossierP);

        //Afficher la page si le dossier patient est bien présent dans patient et dans médecin
            if($droit){
              return $this->Render('meet_my_doc/medecin/dossierPatient(Medecin).html.twig',["dossierPatient" => $dossierP, 'patient' => $patient,'ressources' => $ressources,'idPatient'=>$id]);
            }
            else{
              //S'il n'y a pas de droit
                $this->addFlash('echec-access', 'Vous n\'avez pas les droits d\'accès au dossier patient de '.$patient->getNom().' '.$patient->getPrenom());
                return $this->RedirectToRoute('meet_my_doc_mes_patients');
            }
      }


      /**
      * @Route("/patient/ajouter-medecin-favoris/{id}", name="meet_my_doc_ajouter_medecin_favoris")
      */
      public function ajouterMedecinFavoris(MedecinRepository $repoMedecin, ObjectManager $manager, $id)
      {
        $patient = $this->getUser();

        $medecin = $repoMedecin->findOneById($id);

        foreach ($patient->getMedecinsFavoris() as $leMedecin){
        if($leMedecin == $medecin){
          $this->addFlash('error','Le médecin '.$medecin->getNom().' '.$medecin->getPrenom().' fait déjà parti de vos médecins favoris');
        } else { 
                  $patient->addMedecinsFavori($medecin);
                  $this->addFlash('success','Le médecin '.$medecin->getNom().' '.$medecin->getPrenom().' a bien été ajouté à vos médecins favoris');
               }
        }
        $manager->persist($patient);

        $manager->flush();

        return $this->RedirectToRoute('meet_my_doc_afficher_medecin_favoris');
      }

      /**
      * @Route("/patient/medecins-favoris", name="meet_my_doc_afficher_medecin_favoris")
      */
      public function afficherMedecinFavoris(DossierPatientRepository $repoDossier)
      {
        $patient = $this->getUser();


        $medecins = $this->getUser()->getMedecinsFavoris();

        return $this->Render('meet_my_doc/patient/afficherLesMedecinsFavoris.html.twig', ['medecins' => $medecins, 'patient' => $patient]);
      }



      /**
      * @Route("/patient/retirer-medecin-favoris/{id}", name="meet_my_doc_retirer_medecin_favoris")
      */
      public function retirerMedecinFavoris(MedecinRepository $repoMedecin, ObjectManager $manager, $id)
      {
        $patient = $this->getUser();

        $medecin = $repoMedecin->findOneById($id);

        $patient->removeMedecinsFavori($medecin);

        $manager->persist($patient);

        $manager->flush();

        return $this->RedirectToRoute('meet_my_doc_afficher_medecin_favoris');
      }

//----------------------------- ADMIN -----------------------------//

      /**
      * @Route("/ajouterAdmin", name="meet_my_doc_ajouter_admin")
      */
      public function initAdmin(AdminRepository $repoAdmin, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
      {
        $admin = new Admin();

        $admin->setNom("Lemoine")
              ->setPrenom("Alexandre")
              ->setEmail("admin@meetmydoc.fr")
              ->setRoles(["ROLE_ADMIN"])
              ->setDateNaissance(new \dateTime())
              ->setSexe("Masculin")
              ->setTelephone("0631545352")
              ->setAdresse("1499 route de Cazalis")
              ->setVille("Momuy")
              ->setCodePostal("64600");

            //Encoder le mot de passe
        $encoded = $encoder->encodePassword($admin, "K32zcqp01");
        $admin->setPassword($encoded);

        $manager->persist($admin);

        $manager->flush();

        return $this->RedirectToRoute('accueil');
      }

      // PARTIE DES RESSOURCES DU DOSSIER PATIENT //
      /**
      * @Route("/medecin/dossier-patient/{id}/ajouterRessource", name="meet_my_doc_medecin_ajouter_ressource_dossier_patient")
      */
      public function ajouterRessourceDossierPatient(ObjectManager $manager, Request $request, $id, PatientRepository $repoPatient, DossierPatientRepository $repoDossier)
      {
        // On récupère le patient dont il faut ajouter une ressource dans son dossier patient
        $patient = $repoPatient->findOneById($id);

        // Instancier une nouvelle ressource pour le dossier patient en question
        $ressourceDossierPatient = new RessourceDossierPatient();

        // On récupère le dossier patient
        $dossier = $repoDossier->findOneByPatient($patient);

        //Création du Formulaire permettant d'ajouter une ressource au dossier du patient
        $formulaireRessource = $this->createForm(RessourceDossierPatientType::class, $ressourceDossierPatient);

        $formulaireRessource->handleRequest($request);
  
      //Vérifier que le formulaire a été soumis
        if($formulaireRessource->isSubmitted()){

              $ressourceDossierPatient->setMedecin($this->getUser());

              $ressourceDossierPatient->setDossierPatient($dossier);

              //Enregistrer les donnée en BD
              $manager->persist($ressourceDossierPatient);
              $manager->flush();

              //Redirection vers la page de connexion
              return $this->redirectToRoute('meet_my_doc_dossier_de_mes_patients',['id' => $id]);
          }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireRessource->createView();

      //Envoyer la page à la vue
        return $this->render('meet_my_doc/medecin/medecinAjouterRessourceDossier.html.twig',["formulaire" => $vueFormulaire]);   
      }


      // NE MARCHE PAS : A TERMINER
       /**
       * @Route("/medecin/modifierDossier/{id}", name="meet_my_doc_medecin_modifier_dossier_patient")
       */
      public function medecinModifierDossierPatient(Request $request, ObjectManager $manager,PatientRepository $repoPatient, DossierPatientRepository $repoDossierPatient,$id)
      {
        //Récupérer le patient actuelle
          $medecin=$this->getUser();

          $patient = $repoPatient->findOneById($id);

        //Récupérer son dossier patient
          $dossierPatient = $repoDossierPatient->findOneBy(['patient' => $patient]);

          $ressources = $dossierPatient->getRessourcesDossierPatient();
        //Création du Formulaire permettant de saisir un patient
          $formulaireRessources = $this->createForm(ModifDossierPatientParMedecinType::class, $ressources);

        //Analyse la derniére requete html pour voir si le tableau post
        // contient les variables qui ont été rentrées, si c'est le cas
        // alors il hydrate l'objet user
          $formulaireRessources->handleRequest($request);

          //dump($entreprise);
        //Vérifier que le formulaire a été soumis
          if($formulaireRessources->isSubmitted()){

              //Enregistrer les donnée en BD
                $manager->persist($dossierPatient);
                $manager->flush();

              //Redirection vers la page de connexion
                return $this->redirectToRoute('meet_my_doc_patient_afficher_dossier');
            }

        //Générer la représentation graphique du formulaire
          $vueFormulaire = $formulaireRessources->createView();

        //Envoyer la page à la vue
          return $this->render('meet_my_doc/patient/medecinModifierDossierPatient.html.twig',["formulaire" => $vueFormulaire,"ressources" => $ressources]);
      }

      /**
     * @Route("/medecin/modifierRessource/{id}", name="meet_my_doc_modifier_ressource")
     */
    public function modifierRessource(Request $request, ObjectManager $manager,RessourceDossierPatientRepository $repoRessources, $id)
    {
        $medecin=$this->getUser();

        $medecinId = $medecin->getId();
        $ressource = $repoRessources->findOneById($id);

        $dossierP = $ressource->getDossierPatient();

        $patient = $dossierP->getPatient();

        $id = $patient->getId();

        $formulaireModifierRessource = $this->createForm(RessourceDossierPatientType::class, $ressource);

        $formulaireModifierRessource->handleRequest($request);

        if($formulaireModifierRessource->isSubmitted()){

            //Enregistrer les donnée en BD
              $manager->persist($ressource);
              $manager->flush();

            //Redirection vers la page de connexion
              return $this->redirectToRoute('meet_my_doc_dossier_de_mes_patients',['id'=>$id]);
          }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireModifierRessource->createView();

      //Envoyer la page à la vue
        return $this->render('meet_my_doc/medecin/medecinAjouterRessourceDossier.html.twig',["formulaire" => $vueFormulaire]);
    }

    /**
      *@Route("/medecin/mes-rendezvous", name="meet_my_doc_medecin_afficher_rdv")
      */
      public function afficherLesRDVMedecin(CreneauRepository $repoCreneau)
      {
        //Récupérer le patient actuellement connecté
          $medecin = $this->getUser();

        //Récupérer le mail du patient actuellement connecté
          $id = $medecin->getId();

        //Récupérer les créneaux prix par le patient
          $rdv = $repoCreneau->findRDVetPatientByMedecin($id);

        //Récupérer la date d'aujourd'hui
          $dateAJD = new \dateTime();

        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->render('meet_my_doc/medecin/afficherLesRDV(medecin).html.twig',["creneaux" => $rdv, "dateAJD" => $dateAJD, "medecin" => $medecin]);
      }

      /**
      *@Route("/medecin/annulerRDV-{id}", name="meet_my_doc_medecin_annuler_rdv")
      */
      public function annulerRdvMedecin(MedecinRepository $repoMedecin, CreneauRepository $repoCreneau, ObjectManager $manager, $id=null)
      {
        //Récupérer le créneau à supprimer
          $creneau_a_annuler = $repoCreneau->findOneBy(['id' => $id]);

        //Modifier le créneau
          //Changer état du créneau
            $creneau_a_annuler->setEtat('NON PRIS');

          //Définnir le patient qui a pris le créneau
            $creneau_a_annuler->setPatient(NULL);

            $creneau_a_annuler->setMotif(NULL);

        //Enregistrer le créneau annuler en BD
          //Poser l'etiquette dessus
            $manager->persist($creneau_a_annuler);

          //Modifier le créneau en BD
            $manager->flush();

        //Récupérer le mail du patient actuellement connecté
          $email = $this->getUser()->getEmail();

        //Récupérer les créneaux prix par le patient
          $rdv = $repoCreneau->findCreneauxByPatient($email);

        //Récupérer la date d'aujourd'hui
          $dateAJD = new \dateTime();


        //Envoyer les données du créneau à la vue pour afficher le récapitulatif
          return $this->redirectToRoute('meet_my_doc_medecin_afficher_rdv');
      }
}
