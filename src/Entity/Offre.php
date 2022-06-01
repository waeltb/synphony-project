<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"off"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"off"})
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"off"})
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"off"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"off"})
     */
    private $update_at;

    

  

    /**
     * @ORM\OneToMany(targetEntity=Postulation::class, mappedBy="offre", orphanRemoval=true)
     */
    private $postulations;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, inversedBy="offres")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

   

    public function __construct()
    {
        $this->postulayions = new ArrayCollection();
        $this->postulations = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }
    public function getupdate_at(): ?\DateTimeInterface
    {
        return $this->update_at;
    }
    

    public function setUpdateAt(?\DateTimeInterface $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    

   


    /**
     * @return Collection<int, Postulation>
     */
    public function getPostulations(): Collection
    {
        return $this->postulations;
    }

    public function addPostulation(Postulation $postulation): self
    {
        if (!$this->postulations->contains($postulation)) {
            $this->postulations[] = $postulation;
            $postulation->setOffre($this);
        }

        return $this;
    }

    public function removePostulation(Postulation $postulation): self
    {
        if ($this->postulations->removeElement($postulation)) {
            // set the owning side to null (unless already changed)
            if ($postulation->getOffre() === $this) {
                $postulation->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

   
}
