<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Creneau", mappedBy="patient")
     */
    private $creneaux;

    public function __construct()
    {
        $this->creneaux = new ArrayCollection();
    }


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

    /**
     * @return Collection|Creneau[]
     */
    public function getCreneaux(): Collection
    {
        return $this->creneaux;
    }

    public function addCreneaux(Creneau $creneaux): self
    {
        if (!$this->creneaux->contains($creneaux)) {
            $this->creneaux[] = $creneaux;
            $creneaux->setPatient($this);
        }

        return $this;
    }

    public function removeCreneaux(Creneau $creneaux): self
    {
        if ($this->creneaux->contains($creneaux)) {
            $this->creneaux->removeElement($creneaux);
            // set the owning side to null (unless already changed)
            if ($creneaux->getPatient() === $this) {
                $creneaux->setPatient(null);
            }
        }

        return $this;
    }

}
