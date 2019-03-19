<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedecinRepository")
 */
class Medecin
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idNational;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="medecin", cascade={"persist", "remove"})
     */
    private $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newMedecin = $user === null ? null : $this;
        if ($newMedecin !== $user->getMedecin()) {
            $user->setMedecin($newMedecin);
        }

        return $this;
    }
}
