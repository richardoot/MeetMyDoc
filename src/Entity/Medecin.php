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

}
