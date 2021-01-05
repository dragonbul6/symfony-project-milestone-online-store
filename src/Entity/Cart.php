<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\addProductCart;
use App\Controller\checkoutCart;
use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *      itemOperations = {
            "get" = {
 *              "security" = "is_granted('VIEW',object)",
 *          },
 *          "delete" = {
 *              "security" = "is_granted('REMOVE',object)"
 *          }
 *      },
 *      collectionOperations = {
 *          "post" = {
 *              "path" = "/create-cart",
 *              "controller" = addProductCart::class ,
 *              "security" = "is_granted('ROLE_USER')",
 *              "method" = "POST",
 *              "denormalization_context" = {"groups"={"create:cart"}}            
 *          },
 *           "api_checkout_cart" = {
 *              "path" = "/checkout",
 *              "security" = "is_granted('ROLE_USER')",
 *              "method" = "POST",
 *              "controller" = checkoutCart::class,
 *          }
 *          
 *      }
 * 
 * 
 * )
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="carts")
     */
    private $Buyer;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="carts")
     * @Groups({"user:cart","create:cart"})
     */
    private $product;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addedDate;

    /**
     * 
     * @ORM\Column(type="integer")
     * @Groups({"user:cart"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     * @Groups({"user:cart"})
     */
    private $totalPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuyer(): ?User
    {
        return $this->Buyer;
    }

    public function setBuyer(?User $Buyer): self
    {
        $this->Buyer = $Buyer;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->addedDate;
    }

    public function setAddedDate(\DateTimeInterface $addedDate): self
    {
        $this->addedDate = $addedDate;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }
}
