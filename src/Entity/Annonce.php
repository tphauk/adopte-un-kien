<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;



    /**
     * @ORM\OneToMany(targetEntity=Dog::class, mappedBy="annonce")
     */
    private $dogs;

    /**
     * @ORM\ManyToOne(targetEntity=ElveursSpa::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=true)
     */
    private $eleveurSpa;



    public function __construct()
    {
        $this->dogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }




    /**
     * @return Collection|Dog[]
     */
    public function getDogs(): Collection
    {
        return $this->dogs;
    }

    public function addDog(Dog $dog): self
    {
        if (!$this->dogs->contains($dog)) {
            $this->dogs[] = $dog;
            $dog->setAnnonce($this);
        }

        return $this;
    }

    public function removeDog(Dog $dog): self
    {
        if ($this->dogs->removeElement($dog)) {
            // set the owning side to null (unless already changed)
            if ($dog->getAnnonce() === $this) {
                $dog->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getEleveurSpa(): ?ElveursSpa
    {
        return $this->eleveurSpa;
    }

    public function setEleveurSpa(?ElveursSpa $eleveurSpa): self
    {
        $this->eleveurSpa = $eleveurSpa;

        return $this;
    }

    public function getFirstPhoto(): ?Photo
    {
        foreach ($this->getDogs() as $dog) {
            $photo = $dog->getPhotos()->first();
            if ($photo) {
                return $photo;
            }
        }

        return null;
    }
}
