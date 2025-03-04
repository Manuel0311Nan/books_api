<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['page:read', 'book:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    #[Groups(['page:read'])]
    private ?Book $book = null;

    #[ORM\Column]
    #[Groups(['page:read'])]
    private ?int $pageNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['page:read'])]
    private ?string $content = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['page:read'])]
    private ?array $nextOptions = null;

    #[ORM\Column(length: 255)]
    #[Groups(['page:read'])]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'page', cascade: ['persist', 'remove'])]
    private Collection $items;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
{
    $this->createdAt = new \DateTime; // Asigna la fecha actual al crear la entidad
}
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): static
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getNextOptions(): ?array
    {
        return $this->nextOptions?? [];
    }

    public function setNextOptions(?array $nextOptions): static
    {
        $this->nextOptions = $nextOptions;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

        /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setPage($this);
        }
        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getPage() === $this) {
                $item->setPage(null);
            }
        }
        return $this;
    }
    public function __toString(): string
    {
        $bookTitle = $this->book ? $this->book->getTitle() : 'Sin libro';
        return sprintf('Libro %s - Página %s', $bookTitle, $this->pageNumber ?? 'Sin ID');
    }
    
}
