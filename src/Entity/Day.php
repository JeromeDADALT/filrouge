<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DayRepository")
 */
class Day
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
    private $dayUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayUser(): ?string
    {
        return $this->dayUser;
    }

    public function setDayUser(?string $dayUser): self
    {
        $this->dayUser = $dayUser;

        return $this;
    }
}
