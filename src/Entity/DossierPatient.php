<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DossierPatientRepository")
 */
class DossierPatient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Allergie")
     */
    private $allergies;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MaladieGrave")
     */
    private $maladiesGraves;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vaccin")
     */
    private $vaccins;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Patient", cascade={"persist", "remove"})
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GroupeSanguin")
     */
    private $groupeSanguin;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Medecin")
     */
    private $medecins;

    public function __construct()
    {
        $this->allergies = new ArrayCollection();
        $this->maladiesGraves = new ArrayCollection();
        $this->vaccins = new ArrayCollection();
        $this->medecins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Allergie[]
     */
    public function getAllergies(): Collection
    {
        return $this->allergies;
    }

    public function addAllergy(Allergie $allergy): self
    {
        if (!$this->allergies->contains($allergy)) {
            $this->allergies[] = $allergy;
        }

        return $this;
    }

    public function removeAllergy(Allergie $allergy): self
    {
        if ($this->allergies->contains($allergy)) {
            $this->allergies->removeElement($allergy);
        }

        return $this;
    }

    /**
     * @return Collection|MaladieGrave[]
     */
    public function getMaladiesGraves(): Collection
    {
        return $this->maladiesGraves;
    }

    public function addMaladiesGrave(MaladieGrave $maladiesGrave): self
    {
        if (!$this->maladiesGraves->contains($maladiesGrave)) {
            $this->maladiesGraves[] = $maladiesGrave;
        }

        return $this;
    }

    public function removeMaladiesGrave(MaladieGrave $maladiesGrave): self
    {
        if ($this->maladiesGraves->contains($maladiesGrave)) {
            $this->maladiesGraves->removeElement($maladiesGrave);
        }

        return $this;
    }

    /**
     * @return Collection|Vaccin[]
     */
    public function getVaccins(): Collection
    {
        return $this->vaccins;
    }

    public function addVaccin(Vaccin $vaccin): self
    {
        if (!$this->vaccins->contains($vaccin)) {
            $this->vaccins[] = $vaccin;
        }

        return $this;
    }

    public function removeVaccin(Vaccin $vaccin): self
    {
        if ($this->vaccins->contains($vaccin)) {
            $this->vaccins->removeElement($vaccin);
        }

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getGroupeSanguin(): ?GroupeSanguin
    {
        return $this->groupeSanguin;
    }

    public function setGroupeSanguin(?GroupeSanguin $groupeSanguin): self
    {
        $this->groupeSanguin = $groupeSanguin;

        return $this;
    }

    /**
     * @return Collection|Medecin[]
     */
    public function getMedecins(): Collection
    {
        return $this->medecins;
    }

    public function addMedecin(Medecin $medecin): self
    {
        if (!$this->medecins->contains($medecin)) {
            $this->medecins[] = $medecin;
        }

        return $this;
    }

    public function removeMedecin(Medecin $medecin): self
    {
        if ($this->medecins->contains($medecin)) {
            $this->medecins->removeElement($medecin);
        }

        return $this;
    }
}
