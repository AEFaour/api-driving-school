<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalaryRepository")
 * @ApiResource(
 *     attributes={
 *           "order":{"paidAt":"desc"}
 *     },
 *     normalizationContext={
 *              "groups" = {"salaries_read"}
 *     },
 *     subresourceOperations={
 *          "api_learners_courses_instructors_salaries_get_subresource"={
 *              "normalization_context"={"groups"={"salaries_subresource"}}
 *          }
 *     },
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "DELETE"},
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class)
 */
class Salary
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"salaries_read", "instructors_read", "salaries_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"salaries_read", "instructors_read", "salaries_subresource"})
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"salaries_read", "instructors_read", "salaries_subresource"})
     */
    private $paidAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Instructor", inversedBy="salaries")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("salaries_read")
     */
    private $instructor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeInterface
    {
        return $this->paidAt;
    }

    public function setPaidAt(\DateTimeInterface $paidAt): self
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getInstructor(): ?Instructor
    {
        return $this->instructor;
    }

    public function setInstructor(?Instructor $instructor): self
    {
        $this->instructor = $instructor;

        return $this;
    }
}
