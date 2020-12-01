<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BillingRepository;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"write"}},
 *      itemOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass=BillingRepository::class)
 */
class Billing
{
    /**
     * Unique identifier of the billing
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Product or service name
     *
     * @ApiProperty(attributes={"openapi_context"={"example"="Adidas beach shoes"}})
     * @Assert\Length(max=100)
     * @Assert\NotBlank
     * @Groups({"read","write"})
     * @ORM\Column(type="string",length=100)
     */
    protected $name;

    /**
     * An optional description for billing
     *
     * @ApiProperty(attributes={"openapi_context"={
     *      "example"="Size 7 black color for men"
     * }})
     * @Assert\Length(max=255)
     * @Groups({"read","write"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    protected $description;

    /**
     * The amount of the product or service to be charged
     *
     * @Assert\PositiveOrZero
     * @Assert\NotBlank
     * @Groups({"read","write"})
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * Any discount to apply to the billing amount
     *
     * @Assert\Range(min=0,max=100)
     * @Groups({"read","write"})
     * @ORM\Column(type="float",nullable=true)
     */
    protected $discount;

    /**
     * Total amount to pay
     *
     * @Groups({"read"})
     * @ORM\Column(type="float")
     */
    protected $total;

    /**
     * Email to which the billing is sent
     *
     * @ApiProperty(attributes={"openapi_context"={"example"="demo@domain.com"}})
     * @Assert\Length(max=180)
     * @Assert\Email
     * @Assert\NotBlank
     * @Groups({"read","write"})
     * @ORM\Column(type="string",length=180)
     */
    protected $email;

    /**
     * Unique token that is generated with billing
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $token;

    /**
     * User who generated the billing
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="billings")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * Payment status
     *
     * @Groups({"read"})
     * @ORM\Column(type="boolean")
     */
    protected $status = false;

    /**
     * Construction function
     */
    public function __construct()
    {
        $this->token = Uuid::uuid4();
    }

    /**
     * Returns the billing id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the name of the service or product
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Modify the name of the service or product
     *
     * @param string $name Service or product name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the billing description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Modify the billing description
     *
     * @param string|null $description Description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns the amount of the product or service to pay
     *
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * Modify the amount of the product or service to pay
     *
     * @param float $amount Product or service amount
     *
     * @return self
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this->setTotal();
    }

    /**
     * Returns the discount applied to the billing
     *
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * Modify the discount to be applied in the billing
     *
     * @param float|null $discount Discount to apply
     *
     * @return self
     */
    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this->setTotal();
    }

    /**
     * Returns the total amount to pay
     *
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * Modify the total amount to pay
     *
     * @return self
     */
    protected function setTotal(): self
    {
        $a = $this->getAmount();
        $r = $a*$this->getDiscount()/100;

        $this->total = $a-$r;

        return $this;
    }

    /**
     * Returns the email where the billing is sent
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Modify the email where the billing is sent
     *
     * @param string $email Email address
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Returns the generated token
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Modify the verification token
     *
     * @param string $token Verification token
     *
     * @return self
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Returns the user who generated the billing
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Modify the user who owns the billing
     *
     * @param User|null $user User
     *
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Returns the payment status
     *
     * @return boolean|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * Modify the payment status
     *
     * @param boolean $status Payment status
     *
     * @return self
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
