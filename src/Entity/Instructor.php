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
 * @ORM\Entity(repositoryClass="App\Repository\InstructorRepository")
 * @ApiResource(
 *     normalizationContext={

 *     "groups" = {"instructors_read"}
 *     },
 *     denormalizationContext={
 *     "disable_type_enforcement"=true
 *     },
 *     subresourceOperations={
 *          "salaries_get_subresource"={"path"="/instructors/{id}/salaries"},
 *          "api_learners_courses_instructors_get_subresource"={
 *              "normalization_context"={"groups"={"instructors_subresource"}}
 *          }
 *     },
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "DELETE"},
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class)
 */
class Instructor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"instructors_read", "salaries_read", "courses_read", "instructors_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"instructors_read", "salaries_read", "courses_read", "instructors_subresource"})
     * @Assert\NotBlank(message="Veuillez ajouter le prénom de formateur, svp!")
     * @Assert\Length(min=3, minMessage="Le prénom doit faire au moins 3 caractères !",
     * max=255, maxMessage="Le prénom doit faire 255 caractères maximum")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"instructors_read", "salaries_read", "courses_read", "instructors_subresource"})
     * @Assert\NotBlank(message="Veuillez ajouter le prénom de formateur, svp!")
     * @Assert\Length(min=3, minMessage="Le nom doit faire au moins 3 caractères !",
     * max=255, maxMessage="Le nom doit faire 255 caractères maximum")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"instructors_read", "salaries_read", "courses_read", "instructors_subresource"})
     * @Assert\NotBlank(message="Veuillez ajouter l'email de formateur, svp!")
     * @Assert\Email(message="Veuillez taper le format de l'email correctement")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"instructors_read", "salaries_read", "courses_read", "instructors_subresource"})
     * @Assert\NotBlank(message="Veuillez ajouter le numero de téléphone de formateur, svp!")
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="instructors")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"instructors_read", "instructors_subresource"})
     * @Assert\NotBlank(message="Veuillez ajouter un cours, svp!")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Salary", mappedBy="instructor")
     * @Groups({"instructors_read", "instructors_subresource"})
     * @ApiSubresource()
     */
    private $salaries;

    public function __construct()
    {
        $this->salaries = new ArrayCollection();
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

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection|Salary[]
     */
    public function getSalaries(): Collection
    {
        return $this->salaries;
    }

    public function addSalary(Salary $salary): self
    {
        if (!$this->salaries->contains($salary)) {
            $this->salaries[] = $salary;
            $salary->setInstructor($this);
        }

        return $this;
    }

    public function removeSalary(Salary $salary): self
    {
        if ($this->salaries->contains($salary)) {
            $this->salaries->removeElement($salary);
            // set the owning side to null (unless already changed)
            if ($salary->getInstructor() === $this) {
                $salary->setInstructor(null);
            }
        }

        return $this;
    }

}
