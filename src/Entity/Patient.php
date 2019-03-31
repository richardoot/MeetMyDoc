<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 */
class Patient extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbRDVAnnule;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbRDVAnnule(): ?int
    {
        return $this->nbRDVAnnule;
    }

    public function setNbRDVAnnule(int $nbRDVAnnule): self
    {
        $this->nbRDVAnnule = $nbRDVAnnule;

        return $this;
    }

}
