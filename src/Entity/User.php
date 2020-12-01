<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"write"}},
 *      collectionOperations={
 *          "post"={
 *              "path"="/register"
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "controller"=NotFoundAction::class,
 *              "read"=false,
 *              "output"=false,
 *          }
 *      }
 * )
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * Unique identifier of the user
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * User email
     *
     * @ApiProperty(attributes={"openapi_context"={"example"="demo@domain.com"}})
     * @Assert\Length(max=180)
     * @Assert\Email
     * @Assert\NotBlank
     * @Groups({"read","write"})
     * @ORM\Column(type="string",length=180,unique=true)
     */
    protected $email;

    /**
     * User access roles
     *
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * User password
     *
     * @ApiProperty(attributes={"openapi_context"={"example"="demo"}})
     * @Assert\Length(max=255)
     * @Assert\NotBlank
     * @Groups({"write"})
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * Identification number DNI of the user's country of origin
     *
     * @ApiProperty(attributes={"openapi_context"={"example"="E-12345678"}})
     * @Assert\Length(max=20)
     * @Assert\NotBlank
     * @Groups({"read","write"})
     * @ORM\Column(type="string",length=20)
     */
    protected $document;

    /**
     * User's full name
     *
     * @ApiProperty(attributes={"openapi_context"={"example"="Demo Example"}})
     * @Assert\Length(max=100)
     * @Assert\NotBlank
     * @Groups({"read","write"})
     * @ORM\Column(type="string",length=100)
     */
    protected $name;

    /**
     * User's contact phone
     *
     * @ApiProperty(attributes={"openapi_context"={"example"="+570123456879"}})
     * @Assert\Length(max=20)
     * @Assert\NotBlank
     * @Groups({"read","write"})
     * @ORM\Column(type="string",length=20)
     */
    protected $phone;

    /**
     * User billings
     *
     * @ORM\OneToMany(targetEntity=Billing::class, mappedBy="user")
     */
    protected $billings;

    /**
     * Construction function
     */
    public function __construct()
    {
        $this->billings = new ArrayCollection();
    }

    /**
     * Returns the user id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the user's email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Modify the user's email
     *
     * @param string $email mail address
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     *
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Returns user access roles
     *
     * @see UserInterface
     *
     * @return array
     */
    public function getRoles(): array
    {
        $data = $this->roles;
        // guarantee every user at least has ROLE_USER
        $data[] = 'ROLE_USER';

        return array_unique($data);
    }

    /**
     * Modify user access roles
     *
     * @param array $roles Array with access roles
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the encrypted user password
     *
     * @see UserInterface
     *
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Modify the user's password
     *
     * @param string $password New password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Required function
     *
     * @see UserInterface
     *
     * @return mixed
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * Required function
     *
     * @see UserInterface
     *
     * @return mixed
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Returns the user's document number
     *
     * @return string|null
     */
    public function getDocument(): ?string
    {
        return $this->document;
    }

    /**
     * Modify the user's document number
     *
     * @param string $document Document number
     *
     * @return self
     */
    public function setDocument(string $document): self
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Returns the full name of the user
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Modify the user's full name
     *
     * @param string $name Full name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the user's phone number
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Modify the user's phone number
     *
     * @param string $phone Phone number
     *
     * @return self
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Returns the user's billings
     *
     * @return Collection|Billing[]
     */
    public function getBillings(): Collection
    {
        return $this->billings;
    }

    /**
     * Add a billing
     *
     * @param Billing $billing New billing
     *
     * @return self
     */
    public function addBilling(Billing $billing): self
    {
        if (!$this->billings->contains($billing)) {
            $this->billings[] = $billing;
            $billing->setUser($this);
        }

        return $this;
    }

    /**
     * Delete a billing
     *
     * @param Billing $billing billing
     *
     * @return self
     */
    public function removeBilling(Billing $billing): self
    {
        if ($this->billings->removeElement($billing)) {
            // set the owning side to null (unless already changed)
            if ($billing->getUser() === $this) {
                $billing->setUser(null);
            }
        }

        return $this;
    }
}
