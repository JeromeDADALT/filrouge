<?php

namespace App\Entity;

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
}
