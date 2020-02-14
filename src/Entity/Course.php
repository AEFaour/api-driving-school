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

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 * @ApiResource(
 *     normalizationContext={
 *              "groups" = {"courses_read"}
 *     },
 *     subresourceOperations={
 *          "instructors_get_subresource"={"path"="/courses/{id}/invoices"},
 *          "api_learners_courses_get_subresource"={
 *              "normalization_context"={"groups"={"courses_subresource"}}
 *          }
 *     },
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "DELETE"},
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class)
 */
class Course
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"courses_read", "learners_read", "instructors_read", "courses_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"courses_read", "learners_read", "instructors_read", "courses_subresource"})
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"courses_read", "learners_read", "instructors_read", "courses_subresource"})
     */
    private $duration;

    /**
     * @ORM\Column(type="float")
     * @Groups({"courses_read", "learners_read", "instructors_read", "courses_subresource"})
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Learner", inversedBy="courses")
     * @Groups({"courses_read"})
     */
    private $learner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Instructor", mappedBy="course")
     * @Groups({"courses_read"})
     * @ApiSubresource()
     */
    private $instructors;

    public function __construct()
    {
        $this->learner = new ArrayCollection();
        $this->instructors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Learner[]
     */
    public function getLearner(): Collection
    {
        return $this->learner;
    }

    public function addLearner(Learner $learner): self
    {
        if (!$this->learner->contains($learner)) {
            $this->learner[] = $learner;
        }

        return $this;
    }

    public function removeLearner(Learner $learner): self
    {
        if ($this->learner->contains($learner)) {
            $this->learner->removeElement($learner);
        }

        return $this;
    }

    /**
     * @return Collection|Instructor[]
     */
    public function getInstructors(): Collection
    {
        return $this->instructors;
    }

    public function addInstructor(Instructor $instructor): self
    {
        if (!$this->instructors->contains($instructor)) {
            $this->instructors[] = $instructor;
            $instructor->setCourse($this);
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): self
    {
        if ($this->instructors->contains($instructor)) {
            $this->instructors->removeElement($instructor);
            // set the owning side to null (unless already changed)
            if ($instructor->getCourse() === $this) {
                $instructor->setCourse(null);
            }
        }

        return $this;
    }

}
