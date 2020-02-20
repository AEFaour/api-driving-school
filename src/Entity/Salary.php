<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalaryRepository")
 * @ApiResource(
 *     attributes={
 *           "order":{"paidAt":"desc"}
 *     },
 *     normalizationContext={
 *              "groups" = {"salaries_read"}
 *     },
 *     denormalizationContext={
 *     "disable_type_enforcement"=true
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
     * @Assert\NotBlank(message="Veuillez intégrer le montant du salaire, svp")
     * @Assert\Type(type="numeric", message="Il est impératif que le montant du salaire soit un nombre!")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"salaries_read", "instructors_read", "salaries_subresource"})
     * @Assert\NotBlank(message="Veuillez intégrer la date de payement de salaire, svp")
     * @Assert\Type(type="\DateTime", message="Veuillez respecter le format de la date: YYYY-MM-DD")
     */
    private $paidAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Instructor", inversedBy="salaries")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"salaries_read", "salaries_subresource"})
     * @Assert\NotBlank(message="Veuillez ajouter un formateur, svp!")
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

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeInterface
    {
        return $this->paidAt;
    }

    public function setPaidAt($paidAt): self
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
