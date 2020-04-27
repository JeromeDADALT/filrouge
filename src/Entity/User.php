<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message = "L'email saisi est déjà utilisé.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "L'email doit comporter au maximum {{ limit }} caractères"
     * )
     * @Assert\Email(message = "L'email saisi n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Le mot de passe doit comporter au minimum {{ limit }} caractères"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Le prénom doit comporter au maximum {{ limit }} caractères"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Le nom de l'image doit comporter au maximum {{ limit }} caractères"
     * )
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Regex(
     *     pattern="/[^0-9-+ *]+/",
     *     match=false,
     *     message="Le numéro de téléphone doit être composé de chiffres, d'espaces, d'étoiles, de tirets ou de signes +"
     * )
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Le numéro de téléphone doit comporter au maximum {{ limit }} caractères"
     * )
     */
    private $phoneUser;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Le nom de la ville doit comporter au maximum {{ limit }} caractères"
     * )
     */
    private $cityUser;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      max = 500,
     *      maxMessage = "Votre commentaire doit comporter au maximum {{ limit }} caractères"
     * )
     */
    private $commentUser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    //je crée une association ManyToMany entre User et Level en créant un attribut level dans l'entité User
    //comme la relation est bi directionnelle, j'ajoute inversedBy pour faire une boucle
    // qui va vers la cible Level et qui revient vers users
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Level", inversedBy="users")
     */
    private $level;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Day", inversedBy="users")
     */
    private $day;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Hour", inversedBy="users")
     */
    private $hour;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Structure", inversedBy="users")
     * @ORM\OrderBy({"nameStructure" = "ASC"})
     */
    private $structure;

    //je crée un constructeur pour mettre par défaut un rôle utilisateur et le compte actif
    public function __construct($enable = 1, $roles = ["ROLE_USER"])
    {
        $this->enable = $enable;
        $this->roles = $roles;
        $this->level = new ArrayCollection();
        $this->day = new ArrayCollection();
        $this->hour = new ArrayCollection();
        $this->structure = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        //au moins chaque utilisateur créé aura le role ROLE_USER par défaut
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPhoneUser(): ?string
    {
        return $this->phoneUser;
    }

    public function setPhoneUser(?string $phoneUser): self
    {
        $this->phoneUser = $phoneUser;

        return $this;
    }

    public function getCityUser(): ?string
    {
        return $this->cityUser;
    }

    public function setCityUser(?string $cityUser): self
    {
        $this->cityUser = $cityUser;

        return $this;
    }

    public function getCommentUser(): ?string
    {
        return $this->commentUser;
    }

    public function setCommentUser(?string $commentUser): self
    {
        $this->commentUser = $commentUser;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return Collection|Level[]
     */
    public function getLevel(): Collection
    {
        return $this->level;
    }

    //comme il s'agit d'une relation ManyToMany, il n'y a pas de setter mais un add et un remove
    //car comme il peut y avoir plusieurs réponses, on manipule un array
    public function addLevel(Level $level): self
    {
        if (!$this->level->contains($level)) {
            $this->level[] = $level;
        }

        return $this;
    }

    public function removeLevel(Level $level): self
    {
        if ($this->level->contains($level)) {
            $this->level->removeElement($level);
        }

        return $this;
    }

    /**
     * @return Collection|Day[]
     */
    public function getDay(): Collection
    {
        return $this->day;
    }

    public function addDay(Day $day): self
    {
        if (!$this->day->contains($day)) {
            $this->day[] = $day;
        }

        return $this;
    }

    public function removeDay(Day $day): self
    {
        if ($this->day->contains($day)) {
            $this->day->removeElement($day);
        }

        return $this;
    }

    /**
     * @return Collection|Hour[]
     */
    public function getHour(): Collection
    {
        return $this->hour;
    }

    public function addHour(Hour $hour): self
    {
        if (!$this->hour->contains($hour)) {
            $this->hour[] = $hour;
        }

        return $this;
    }

    public function removeHour(Hour $hour): self
    {
        if ($this->hour->contains($hour)) {
            $this->hour->removeElement($hour);
        }

        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStructure(): Collection
    {
        return $this->structure;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structure->contains($structure)) {
            $this->structure[] = $structure;
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structure->contains($structure)) {
            $this->structure->removeElement($structure);
        }

        return $this;
    }
}
