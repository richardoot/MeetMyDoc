<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedecinRepository")
 */
class Medecin extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $idNational;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Creneau", mappedBy="medecin", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $creneaux;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Specialite", inversedBy="medecins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $specialite;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Patient")
     */
    private $patients;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\DossierPatient", inversedBy="medecins")
     */
    private $dossierPatient;


    public function __construct()
    {
        $this->creneaux = new ArrayCollection();
        $this->patients = new ArrayCollection();
        $this->dossierPatient = new ArrayCollection();
    }

    public function getIdNational(): ?string
    {
        return $this->idNational;
    }

    public function setIdNational(string $idNational): self
    {
        $this->idNational = $idNational;

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
            $creneaux->setMedecin($this);
        }

        return $this;
    }

    public function removeCreneaux(Creneau $creneaux): self
    {
        if ($this->creneaux->contains($creneaux)) {
            $this->creneaux->removeElement($creneaux);
            // set the owning side to null (unless already changed)
            if ($creneaux->getMedecin() === $this) {
                $creneaux->setMedecin(null);
            }
        }

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->contains($patient)) {
            $this->patients->removeElement($patient);
        }

        return $this;
    }

    public function isMedecinFavori(Patient $patient): ?bool
    {
        if( $patient == NULL ){
            return false;
        }
        foreach ($patient->getMedecinsFavoris() as $medecin) {
            if($medecin == $this){
              return true;
            }
          }
        return false;
    }


    /**
     * @return Collection|DossierPatient[]
     */
    public function getDossierPatient(): Collection
    {
        return $this->dossierPatient;
    }

    public function addDossierPatient(DossierPatient $dossierPatient): self
    {
        if (!$this->dossierPatient->contains($dossierPatient)) {
            $this->dossierPatient[] = $dossierPatient;
        }

        return $this;
    }

    public function removeDossierPatient(DossierPatient $dossierPatient): self
    {
        if ($this->dossierPatient->contains($dossierPatient)) {
            $this->dossierPatient->removeElement($dossierPatient);
        }

        return $this;
    }


    public function isGranted(DossierPatient $dossier): ?bool
    {
        foreach ($dossier->getMedecins() as $medecin) {
            if($medecin == $this){
              return true;
            }
          }
          return false;
    }

    public function getId(): ?int
    {
        //return $this->id;
        return parent::getId();
    }
}
