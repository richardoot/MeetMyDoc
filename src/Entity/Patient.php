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
    private $nbRDVAnnule;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="patient", cascade={"persist", "remove"})
     */
    private $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newPatient = $user === null ? null : $this;
        if ($newPatient !== $user->getPatient()) {
            $user->setPatient($newPatient);
        }

        return $this;
    }
}
