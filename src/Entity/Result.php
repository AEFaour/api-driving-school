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
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 * @ApiResource(
 *     normalizationContext={
 *              "groups" = {"results_read"}
 *     },
 *      denormalizationContext={
 *     "disable_type_enforcement"=true
 *     },
 *     subresourceOperations={
 *          "api_learners_results_get_subresource"={
 *              "normalization_context"={"groups"={"results_subresource"}}
 *          }
 *     },
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "DELETE"},
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class)
 */
class Result
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"results_read", "learners_read", "results_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"results_read", "learners_read", "results_subresource"})
     * @Assert\NotBlank(message="Veuillez préciser le status de resultat! ")
     * @Assert\Choice(choices={"ADMITTED", "ADJOURNED"},
     *          message="Veuillez choisir un status de resultat parmi les options suivants : ADMITTED et ADJOURNED! ")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"results_read", "learners_read", "results_subresource"})
     * @Assert\NotBlank(message="Veuillez intégrer la date de resultat, svp")
     * @Assert\Type(type="\DateTime", message="Veuillez respecter le format de la date: YYYY-MM-DD")
     */
    private $obtainedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Learner", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"results_read", "results_subresource"})
     * @Assert\NotBlank(message="Veuillez indiquer le stagiaire concerné par la resultat ")
     */
    private $learner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getObtainedAt(): ?\DateTimeInterface
    {
        return $this->obtainedAt;
    }

    public function setObtainedAt($obtainedAt): self
    {
        $this->obtainedAt = $obtainedAt;

        return $this;
    }

    public function getLearner(): ?Learner
    {
        return $this->learner;
    }

    public function setLearner(?Learner $learner): self
    {
        $this->learner = $learner;

        return $this;
    }
}
