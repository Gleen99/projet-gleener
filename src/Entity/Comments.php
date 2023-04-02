<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\Column]
    private ?int $likes = 0;

    #[ORM\Column]
    private ?int $dislikes = 0;

   

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'creator_id', nullable: false)]
    private ?User $creator = null;


    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Posts $post = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: Reports::class)]
    private Collection $reports;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }
    public function addLike()
    {
        $this->likes++;
    }
    public function setLikes($likes)
{
    $this->likes = $likes;
}

public function setDislikes($dislikes)
{
    $this->dislikes = $dislikes;
}

    
    public function addDislike()
    {
        $this->dislikes++;
    }
    
    public function removeLike()
    {
        $this->likes--;
    }
    
    public function removeDislike()
    {
        $this->dislikes--;
    }
    
    public function getLikes(): int
    {
        return $this->likes;
    }
    
    public function getDislikes(): int
    {
        return $this->dislikes;
    }
    
    public function getUser(): ?User
    {
        return $this->creator;
    }

    public function setUser(?User $user): self
    {
        $this->creator = $user;

        return $this;
    }

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    public function setPost(?Posts $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Collection<int, Reports>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Reports $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setComment($this);
        }

        return $this;
    }

    public function removeReport(Reports $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getComment() === $this) {
                $report->setComment(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getText();
    }
}

