<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={"post"},
 *     itemOperations={"get", "put"},
 *     normalizationContext={
 *         "groups"={"cart:read"}
 *     },
 *     denormalizationContext={
 *         "groups"={"cart:write"},
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="cart", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"cart:write", "cart:read", "users:write"})
     */
    private $user;

    /**
     * @var Collection|Product[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="carts")
     * @Groups({"cart:read", "cart:write"})
     * @ApiSubresource
     * @Assert\Count(
     *      max = 3,
     *      maxMessage = "You cannot add more than {{ limit }} products to your cart."
     * )
     */
    private $products;

    /**
     * @var Money
     */
    private $total;

    /**
     * Cart constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user     = $user;
        $user->setCart($this);
        $this->products = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        if ($user->getCart() !== $this) {
            $user->setCart($this);
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

    /**
     * @Groups({"cart:read"})
     *
     * @return Money|null
     */
    public function getTotal(): ?Money
    {
        if ($this->products->isEmpty()) {
            return null;
        }

        $total = new Money(0, new Currency(Product::DEFAULT_CURRENCY));
        /** @var Product $product */
        foreach ($this->products as $product) {
            $total = $total->add($product->getMoney());
        }

        return $total;
    }
}
