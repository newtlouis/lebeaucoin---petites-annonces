<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AnnoncesRepository::class)
 * @ORM\Table(name="annonces", indexes={@ORM\Index(columns={"title", "content"}, flags={"fulltext"})})
 */
class Annonces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="annonces", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favoris")
     */
    private $favoris;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    // public function setSlug(string $slug): self
    // {
    //     $this->slug = $slug;

    //     return $this;
    // }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    // public function setCreatedAt(\DateTimeImmutable $created_at): self
    // {
    //     $this->created_at = $created_at;

    //     return $this;
    // }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnnonces($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnnonces() === $this) {
                $image->setAnnonces(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(User $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }

    public function removeFavori(User $favori): self
    {
        $this->favoris->removeElement($favori);

        return $this;
    }
}
