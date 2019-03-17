<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
