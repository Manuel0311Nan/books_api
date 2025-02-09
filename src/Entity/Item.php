<?php

namespace App\Entity;

use App\Enum\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $power = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: "objects")]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Page $page;

    #[ORM\OneToOne(targetEntity: Book::class, inversedBy: "rune", cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Book $book = null;

    #[ORM\Column(type: "datetime")]
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?ItemType
    {
        return $this->type ? ItemType::from($this->type) : null;
    }
    
    public function setType(ItemType $type): static
    {
        $this->type = $type->value;
        return $this;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getPower():?string
    {
        return $this->power;
    }
    public function setPower(?string $power): static
    {
        $this->power = $power;
        return $this;
    }

    public function getImage():?string
    {
        return $this->image;
    }
    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }
    public function setPage(?Page $page): static
{
    if ($page) {
        $book = $page->getBook();

        if ($this->book && $this->page && $this->book->getId() === $book->getId() && $this->page->getId() === $page->getId()) {
            throw new \Exception('Este ítem ya está asignado a esta página del libro.');
        }

        $this->book = $book;
    }

    $this->page = $page;
    return $this;
}
    public function getBook():?Book
    {
        return $this->book;
    }
    public function setBook(?Book $book): static
    {
        $this->book = $book;
        return $this;
    }


}
