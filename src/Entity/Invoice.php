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
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 * @ApiResource(
 *     attributes={
 *           "pagination_enabled"=false,
 *           "pagination_items_per_page": 20,
 *           "order":{"sentAt":"desc"}
 *     },
 *     subresourceOperations={
 *          "api_learners_invoices_get_subresource"={
 *              "normalization_context"={"groups"={"invoices_subresource"}}
 *          }
 *     },
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "DELETE"},
 *     normalizationContext={
 *              "groups" = {"invoices_read"}
 *     },
 *     denormalizationContext={
 *     "disable_type_enforcement"=true
 *     }
 * )
 * @ApiFilter(OrderFilter::class, properties={"amount", "sentAt"})
 * @ApiFilter(SearchFilter::class)
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"invoices_read", "learners_read", "invoices_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoices_read", "learners_read", "invoices_subresource"})
     * @Assert\NotBlank(message="Veuillez intégrer le montant de la facture, svp")
     * @Assert\Type(type="numeric", message="Il est impératif que le montant de la facture soit un nombre!")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"invoices_read", "learners_read", "invoices_subresource"})
     * @Assert\NotBlank(message="Veuillez intégrer la date de l'envoi de la facture, svp")
     * @Assert\Type(type="\DateTime", message="Veuillez respecter le format de la date: YYYY-MM-DD")
     */
    private $sentAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoices_read", "learners_read", "invoices_subresource"})
     * @Assert\NotBlank(message="Veuillez préciser le status de la facture! ")
     * @Assert\Choice(choices={"SENT", "PAID", "CANCELLED"},
     *          message="Veuillez choisir un status de la facture parmi les options suivants : SENT, PAID et CANCELLED! ")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Learner", inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"invoices_read"})
     * @Assert\NotBlank(message="Veuillez indiquer le stagiaire concerné par la facture ")
     */
    private $learner;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"invoices_read",  "learners_read", "invoices_subresource"})
     * @Assert\NotBlank(message="Veuillez intégrer le chrono de la facture ")
     * @Assert\Type(type="integer", message="Il est impératif que le chrono de la facture soit un nombre! " )
     */
    private $chrono;

    /**
     * Permet de récupérer le user qui appartient la facture
     * @Groups({"invoices_read"})
     * @return User
     */
    public function getUser() : User {
        return $this->learner->getUser();
    }

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

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt($sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
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

    public function getLearner(): ?Learner
    {
        return $this->learner;
    }

    public function setLearner(?Learner $learner): self
    {
        $this->learner = $learner;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    public function setChrono($chrono): self
    {
        $this->chrono = $chrono;

        return $this;
    }
}
