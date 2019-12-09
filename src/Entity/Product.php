<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "put", "delete"},
 *     normalizationContext={
 *         "groups"={"products:read"},
 *         "swagger_definition_name"="Read"
 *     },
 *     denormalizationContext={
 *         "groups"={"products:write"},
 *         "swagger_definition_name"="Write"
 *     },
 *     shortName="games"
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity("title")
 */
class Product
{
    public const DEFAULT_CURRENCY = 'USD';
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Groups({"products:read", "products:write", "cart:read"})
     */
    private $title;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({ "products:read", "products:write"})
     */
    private $price;

    /**
     * @var Collection|Cart[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Cart", mappedBy="products")
     * @Groups({"cart:write"})
     */
    private $carts;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @Groups({"products:read", "cart:read"})
     *
     * @return Money|null
     */
    public function getMoney(): ?Money
    {
        return new Money($this->price, new Currency(self::DEFAULT_CURRENCY));
    }

    /**
     * @param int|null $price
     * @return $this
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    /**
     * @param Cart $cart
     * @return $this
     */
    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->addProduct($this);
        }

        return $this;
    }

    /**
     * @param Cart $cart
     * @return $this
     */
    public function removeCart(Cart $cart): self
    {
        if ($this->carts->contains($cart)) {
            $this->carts->removeElement($cart);
            $cart->removeProduct($this);
        }

        return $this;
    }
}
