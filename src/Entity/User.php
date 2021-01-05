<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource(
 *      itemOperations = {
 *              "get" ={
 *                  "security" = "is_granted('VIEW',object)",
 *                  "normalization_context" = {"groups" = {"read"}}
 *              },
 *              "put" = {
 *                  "security" = "is_granted('WRITE',object)",
 *                  "normalization_context" = {"groups" = {"get"}},
 *                  "denormalization_context" = {"groups" = {"put"}}
 *              },
 *              "api_users_cart_subresource" = {
 *                  "method" = "GET",
 *                  "path" = "/users/{id}/carts",
 *                  "security" = "is_granted('VIEW',object)",
 *                  "normalization_context" = {"groups" = {"user:cart"}}
 *              }
 *          },
 *      collectionOperations = {
 *              "post" = {
 *                  "denormalization_context" = {"groups" = {"post"}},
 *                  "normalization_context" = {"groups" = {"get"}}
 *              },
 *              "get" = {
 *                  "access_control" = "is_granted('ROLE_ADMIN')",
 *                  "normalization_context" = {"groups" = {"admin:read"}}
 *              }
 *          }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User implements UserInterface
{

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_SELLER = 'ROLE_SELLER';

    const DEFAULT_ROLE = [self::ROLE_USER];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @NotBlank()
     * @Groups({"post","read","get","user:cart"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post"})
     * @NotBlank()
     * @Length(min = 4,minMessage = "Password require 4 charecters.")
     */
    private $password;

    /**
     * @ORM\Column(type="simple_array", length=200)
     * @Groups({"admin:read","get","post"})
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post","get","put","admin:read","read"})
     * @NotBlank()
     * @Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"post","get","put","admin:read","read"})
     * @Length(min = 10 , minMessage = "The telephone number require 10 charecter")
     */
    private $tel;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="seller")
     * @Groups({"read"})
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="Buyer")
     * @Groups({"user:cart"})   
     */
    private $carts;

    public function __construct()
    {
        $this->roles = self::DEFAULT_ROLE;
        $this->products = new ArrayCollection();
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function setRoles(array $role)
    {
        $this->roles = $role;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setSeller($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSeller() === $this) {
                $product->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setBuyer($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getBuyer() === $this) {
                $cart->setBuyer(null);
            }
        }

        return $this;
    }
}
