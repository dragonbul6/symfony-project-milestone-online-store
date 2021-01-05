<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      itemOperations = {
 *          "get" = {
 *              "security" = "is_granted('VIEW',object)"
 *           },
 *          "put" = {
 *              "security" = "is_granted('EDIT',object)",
 *              "denormalization_context" = {"groups" = {"put"}},
 *              "normalization_context" = {"groups" = {"put"}}
 *          },
 *          "delete" = {
 *              "security" = "is_granted('REMOVE',object)"
 *          }
 *      },
 *      collectionOperations = {
 *          "post" = {
 *              "security" = "is_granted('ROLE_SELLER')"
 *          },
 *          "get"
 *      }
 * )
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"put","read","user:cart"})
     */
    private $productName;

    /**
     * @ORM\Column(type="float")
     * @Groups({"put","read"})
     */
    private $productPrice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $productDate;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     */
    private $seller;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="product")
     */
    private $carts;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductDate(): ?\DateTimeInterface
    {
        return $this->productDate;
    }

    public function setProductDate(\DateTimeInterface $productDate): self
    {
        $this->productDate = $productDate;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): self
    {
        $this->seller = $seller;

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
            $cart->setProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getProduct() === $this) {
                $cart->setProduct(null);
            }
        }

        return $this;
    }
}
