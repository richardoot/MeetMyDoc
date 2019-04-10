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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Medecin")
     */
    private $medecinsFavoris;



    public function __construct()
    {
        $this->creneaux = new ArrayCollection();
        $this->medecinsFavoris = new ArrayCollection();
    }


    public function getId(): ?int
    {
        //return $this->id;
        return parent::getId();
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

    /**
     * @return Collection|Medecin[]
     */
    public function getMedecinsFavoris(): Collection
    {
        return $this->medecinsFavoris;
    }

    public function addMedecinsFavori(Medecin $medecinsFavori): self
    {
        if (!$this->medecinsFavoris->contains($medecinsFavori)) {
            $this->medecinsFavoris[] = $medecinsFavori;
        }

        return $this;
    }

    public function removeMedecinsFavori(Medecin $medecinsFavori): self
    {
        if ($this->medecinsFavoris->contains($medecinsFavori)) {
            $this->medecinsFavoris->removeElement($medecinsFavori);
        }

        return $this;
    }

    public function getDossierPatient(): ?DossierPatient
    {
        return $this->dossierPatient;
    }

    public function setDossierPatient(?DossierPatient $dossierPatient): self
    {
        $this->dossierPatient = $dossierPatient;

        // set (or unset) the owning side of the relation if necessary
        $newPatient = $dossierPatient === null ? null : $this;
        if ($newPatient !== $dossierPatient->getPatient()) {
            $dossierPatient->setPatient($newPatient);
        }

        return $this;
    }

}
