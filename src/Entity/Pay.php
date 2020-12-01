<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PayRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=PayRepository::class)
 */
class Pay
{
    /**
     * Unique identifier of the payment
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Payment amount
     *
     * @Assert\PositiveOrZero
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * Billing to which the payment belongs
     *
     * @ORM\ManyToOne(targetEntity=Billing::class, inversedBy="pays")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $billing;

    /**
     * Date of payment
     *
     * @ORM\Column(type="datetime")
     */
    protected $createAt;

    /**
     * Construction function
     */
    public function __construct()
    {
        $this->setCreateAt(new \DateTime());
    }

    /**
     * Returns the payment identifier
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the payment amount
     *
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return round($this->amount, 2);
    }

    /**
     * Modify the payment amount
     *
     * @param float $amount Amount
     *
     * @return self
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Returns the billing to which the payment belongs
     *
     * @return Billing|null
     */
    public function getBilling(): ?Billing
    {
        return $this->billing;
    }

    /**
     * Modify the billing to which the payment belongs
     *
     * @param Billing|null $billing Billing
     *
     * @return self
     */
    public function setBilling(?Billing $billing): self
    {
        $this->billing = $billing;

        return $this;
    }

    /**
     * Returns the payment date
     *
     * @return \DateTimeInterface|null
     */
    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * Modify the payment date
     *
     * @param \DateTimeInterface $createAt Date of payment
     *
     * @return self
     */
    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Validate the amount entered
     *
     * @Assert\Callback
     */
    public function validateAmount(ExecutionContextInterface $context)
    {
        if ($this->getAmount() > $this->getBilling()->getPending()) {
            $context
                ->buildViolation('The amount entered is greater than the pending!')
                ->atPath('amount')
                ->addViolation();
        }
    }
}
