<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(
 *     normalizationContext={"groups" = {"users_read"}},
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "DELETE"},
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class)
 * @UniqueEntity("email", message="Un autre user a déjà utilisé ce mail")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"learners_read", "invoices_read", "courses_read", "results_read", "users_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"learners_read", "invoices_read", "courses_read", "results_read", "users_read"})
     * @Assert\NotBlank(message="Il est impératif d'intégrer le mail élctronique ! ")
     * @Assert\Email(message="Il est impératif que la mail soit valid")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"learners_read", "invoices_read", "courses_read", "results_read", "users_read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"learners_read", "invoices_read", "courses_read", "results_read"})
     * @Assert\NotBlank(message="Il est impératif d'intégrer le mot de pass ! ")
     */
    private $password;

    /** "invoices_read"
     * @ORM\Column(type="string", length=255)
     * @Groups({"learners_read", "invoices_read", "courses_read", "results_read", "users_read"})
     * @Assert\NotBlank(message="Il est impératif d'intégrer le prénom ! ")
     * @Assert\Length(min=3, minMessage="Il est impératif que le prénom fasse entre 3 et 255 caractères",
     *     max=255, maxMessage="Il est impératif que le prénom fasse entre 3 et 255 caractères")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"learners_read", "invoices_read", "courses_read", "results_read", "users_read"})
     * @Assert\NotBlank(message="Il est impératif d'intégrer le nom ! ")
     * @Assert\Length(min=3, minMessage="Il est impératif que le nom fasse entre 3 et 255 caractères",
     *     max=255, maxMessage="Il est impératif que le nom fasse entre 3 et 255 caractères")
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Learner", mappedBy="user")
     */
    private $learners;

    public function __construct()
    {
        $this->learners = new ArrayCollection();
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
        // guarantee every user at least has ROLE_USER
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|Learner[]
     */
    public function getLearners(): Collection
    {
        return $this->learners;
    }

    public function addLearner(Learner $learner): self
    {
        if (!$this->learners->contains($learner)) {
            $this->learners[] = $learner;
            $learner->setUser($this);
        }

        return $this;
    }

    public function removeLearner(Learner $learner): self
    {
        if ($this->learners->contains($learner)) {
            $this->learners->removeElement($learner);
            // set the owning side to null (unless already changed)
            if ($learner->getUser() === $this) {
                $learner->setUser(null);
            }
        }

        return $this;
    }
}
