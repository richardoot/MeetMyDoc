<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RessourceDossierPatientRepository")
 */
class RessourceDossierPatient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeRessourceDossierPatient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeRessourceDossierPatient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DossierPatient", inversedBy="ressourcesDossierPatient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dossierPatient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlRessource;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Medecin")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medecin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeRessourceDossierPatient(): ?TypeRessourceDossierPatient
    {
        return $this->typeRessourceDossierPatient;
    }

    public function setTypeRessourceDossierPatient(?TypeRessourceDossierPatient $typeRessourceDossierPatient): self
    {
        $this->typeRessourceDossierPatient = $typeRessourceDossierPatient;

        return $this;
    }

    public function getDossierPatient(): ?DossierPatient
    {
        return $this->dossierPatient;
    }

    public function setDossierPatient(?DossierPatient $dossierPatient): self
    {
        $this->dossierPatient = $dossierPatient;

        return $this;
    }

    public function getUrlRessource(): ?string
    {
        return $this->urlRessource;
    }

    public function setUrlRessource(string $urlRessource): self
    {
        $this->urlRessource = $urlRessource;

        return $this;
    }

    public function getMedecin(): ?Medecin
    {
        return $this->medecin;
    }

    public function setMedecin(?Medecin $medecin): self
    {
        $this->medecin = $medecin;

        return $this;
    }
}
