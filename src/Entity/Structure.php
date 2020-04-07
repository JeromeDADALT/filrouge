<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StructureRepository")
 */
class Structure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nameStructure;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $cityStructure;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phoneStructure;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $linkStructure;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentStructure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameStructure(): ?string
    {
        return $this->nameStructure;
    }

    public function setNameStructure(string $nameStructure): self
    {
        $this->nameStructure = $nameStructure;

        return $this;
    }

    public function getCityStructure(): ?string
    {
        return $this->cityStructure;
    }

    public function setCityStructure(string $cityStructure): self
    {
        $this->cityStructure = $cityStructure;

        return $this;
    }

    public function getPhoneStructure(): ?string
    {
        return $this->phoneStructure;
    }

    public function setPhoneStructure(?string $phoneStructure): self
    {
        $this->phoneStructure = $phoneStructure;

        return $this;
    }

    public function getLinkStructure(): ?string
    {
        return $this->linkStructure;
    }

    public function setLinkStructure(?string $linkStructure): self
    {
        $this->linkStructure = $linkStructure;

        return $this;
    }

    public function getCommentStructure(): ?string
    {
        return $this->commentStructure;
    }

    public function setCommentStructure(?string $commentStructure): self
    {
        $this->commentStructure = $commentStructure;

        return $this;
    }
}
