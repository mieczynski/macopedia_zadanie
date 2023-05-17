<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Products
{
    const INDEX_LENGTH = 8;
    const MIN_PRODUCT_LENGTH = 1;
    const MAX_PRODUCT_LENGTH = 128;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private string $id;

    /**
     * @var string
     *
     * @ORM\Column(name="product_index", type="smallint", unique=true)
     */
    private string $index;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private string $name;


    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="product")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     */
    public $category = null;

    /**
     * @param string $name
     * @param int $index
     */
    public function __construct(string $name, int $index)
    {
        $this->name = $name;
        $this->index = $index;
    }

    public function __serialize(): array
    {
        return ['id' => $this->getId()];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
    }

    public function __initializer__()
    {

    }

    public static function getValidFields(): array
    {
        return ['nazwa produktu', 'index produktu'];
    }

    public function validProduct(): bool
    {
        return strlen($this->name) < self::MAX_PRODUCT_LENGTH && strlen($this->name) > self::MIN_PRODUCT_LENGTH;
    }

    public function validIndex(): bool
    {
        return strlen($this->index) != self::INDEX_LENGTH;
    }

    public function toString(): string
    {
        return 'Products(' . $this->name . ', ' . $this->index . ')';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndex(): ?int
    {
        return $this->index;
    }

    public function setIndex(int $index): self
    {
        $this->index = $index;

        return $this;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'index' => $this->index,
            'category' => $this->category ?: '',
        ];
    }
}