<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LevelRepository")
 */
class Level
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $levelUser;

    //je crée une association ManyToMany entre User et Level en créant un attribut users dans l'entité Level
    //comme la relation est bi directionnelle, j'ajoute mappdeBy pour faire une boucle
    // qui va vers la cible User et qui revient vers level
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="level", cascade={"remove"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevelUser(): ?string
    {
        return $this->levelUser;
    }

    public function setLevelUser(?string $levelUser): self
    {
        $this->levelUser = $levelUser;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    //comme il s'agit d'une relation ManyToMany, il n'y a pas de setter mais un add et un remove
    //car comme il peut y avoir plusieurs réponses, on manipule un array
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addLevel($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeLevel($this);
        }

        return $this;
    }

}
