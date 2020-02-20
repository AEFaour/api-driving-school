<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LearnerRepository")
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "DELETE"},
 *      subresourceOperations={
 *          "invoices_get_subresource"={"path"="/learners/{id}/invoices"},
 *          "results_get_subresource"={"path"="/learners/{id}/results"},
 *          "courses_get_subresource"={"path"="/learners/{id}/courses"}
 *     },
 *     normalizationContext={
 *           "groups"={"learners_read"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class)
 */
class Learner
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"learners_read","invoices_read", "courses_read", "results_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"learners_read","invoices_read", "courses_read", "results_read"})
     * @Assert\NotBlank(message="Veuillez ajouter le prénom de stagiaire, svp!")
     * @Assert\Length(min=3, minMessage="Le prénom doit faire au moins 3 caractères !",
     * max=255, maxMessage="Le prénom doit faire 255 caractères maximum")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"learners_read","invoices_read", "courses_read", "results_read"})
     * @Assert\NotBlank(message="Veuillez ajouter le nom de stagiaire, svp!")
     * @Assert\Length(min=3, minMessage="Le nom doit faire au moins 3 caractères !",
     * max=255, maxMessage="Le nom doit faire 255 caractères maximum")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"learners_read","invoices_read", "courses_read", "results_read"})
     * @Assert\NotBlank(message="Veuillez ajouter l'email de stagiaire, svp!")
     * @Assert\Email(message="Veuillez taper le format de l'email correctement")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"learners_read","invoices_read", "courses_read", "results_read"})
     * @Assert\NotBlank(message="Veuillez ajouter le numero de téléphone de stagiaire, svp!")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"learners_read","invoices_read", "courses_read", "results_read"})
     */
    private $job;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="learner")
     * @Groups({"learners_read"})
     * @ApiSubresource()
     */
    private $invoices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="learner")
     * @Groups({"learners_read"})
     * @ApiSubresource()
     */
    private $results;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Course", mappedBy="learner")
     * @Groups({"learners_read"})
     * @ApiSubresource()
     */
    private $courses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="learners")
     * @Groups({"learners_read","invoices_read", "courses_read", "results_read"})
     * @Assert\NotBlank(message="Veuillez ajouter un utilisateur, svp!")
     */
    private $user;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    /**
     * #Pour récuperer le montant total des factures
     * @Groups({"learners_read"})
     * @return float
     */
    public function getTotalAmount(): float
    {
        return array_reduce($this->invoices->toArray(), function ($total, $invoice){
            return $total + $invoice ->getAmount();
        }, 0);
    }

    /**
     * # Pour récuperer le montant total non pays
     * @Groups({"learners_read"})
     * @return float
     */
    public function getUnpaidAmount(): float
    {
        return array_reduce($this->invoices->toArray(), function ($total, $invoice){
            return $total + ($invoice->getStatus() === "PAID" || $invoice->getStatus() === "CANCELLED" ? 0 : $invoice ->getAmount() );
        }, 0);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setLearner($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getLearner() === $this) {
                $invoice->setLearner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setLearner($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getLearner() === $this) {
                $result->setLearner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->addLearner($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
            $course->removeLearner($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
