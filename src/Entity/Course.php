<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 */
class Course
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $duration;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Learner", inversedBy="courses")
     */
    private $learner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Instructor", mappedBy="course")
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
