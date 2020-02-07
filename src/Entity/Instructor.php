<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstructorRepository")
 */
class Instructor
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Learner", mappedBy="instructor")
     */
    private $learners;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Address", mappedBy="instructor", cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Salary", mappedBy="instructor", cascade={"persist", "remove"})
     */
    private $salary;


    public function __construct()
    {
        $this->learners = new ArrayCollection();
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
            $learner->setInstructor($this);
        }

        return $this;
    }

    public function removeLearner(Learner $learner): self
    {
        if ($this->learners->contains($learner)) {
            $this->learners->removeElement($learner);
            // set the owning side to null (unless already changed)
            if ($learner->getInstructor() === $this) {
                $learner->setInstructor(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        // set the owning side of the relation if necessary
        if ($address->getInstructor() !== $this) {
            $address->setInstructor($this);
        }

        return $this;
    }

    public function getSalary(): ?Salary
    {
        return $this->salary;
    }

    public function setSalary(Salary $salary): self
    {
        $this->salary = $salary;

        // set the owning side of the relation if necessary
        if ($salary->getInstructor() !== $this) {
            $salary->setInstructor($this);
        }

        return $this;
    }

}
