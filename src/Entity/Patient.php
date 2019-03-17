<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 */
class Patient
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
    private $nbRDVannule;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbRDVannule(): ?int
    {
        return $this->nbRDVannule;
    }

    public function setNbRDVannule(int $nbRDVannule): self
    {
        $this->nbRDVannule = $nbRDVannule;

        return $this;
    }
}
