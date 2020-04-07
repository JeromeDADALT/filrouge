<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HourRepository")
 */
class Hour
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
    private $hourUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHourUser(): ?string
    {
        return $this->hourUser;
    }

    public function setHourUser(?string $hourUser): self
    {
        $this->hourUser = $hourUser;

        return $this;
    }
}
