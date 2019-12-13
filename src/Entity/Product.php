<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
     * Product constructor.
     */
    public function __construct(string $title, ?int $price)
    {
        $this->title = $title;
        $this->price = $price;
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
}
