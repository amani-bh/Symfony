<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Recipe
 *
 * @ORM\Table(name="recipe")
 * @ORM\Entity
 */
class Recipe
{
    public $className='Recipe';
    /**
     * @var int
     *
     * @Groups("post:read")
     * @ORM\Column(name="recipe_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $recipeId;

    /**
     * @var string
     *
     * @Groups("post:read")
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Fill this field!")
     */
    private $title = '';

    /**
     * @var string
     *
     * @Groups("post:read")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Fill this field!")

     */
    private $description = '';

    /**
     * @var string|null
     *
     * @Groups("post:read")
     * @ORM\Column(name="ingredients", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Fill this field!")
     */
    private $ingredients;

    /**
     * @var string|null
     *
     * @Groups("post:read")
     * @ORM\Column(name="steps", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Fill this field!")
     */
    private $steps;

    /**
     * @var int|null
     *
     * @Groups("post:read")
     * @ORM\Column(name="duration", type="integer", nullable=true)
     * @Assert\NotBlank(message="Fill this field!")
     */
    private $duration;

    /**
     * @var int|null
     *
     * @Groups("post:read")
     * @ORM\Column(name="persons", type="integer", nullable=true)
     * @Assert\NotBlank(message="Fill this field!")
     */
    private $persons;

    /**
     * @var int|null
     *
     * @Groups("post:read")
     * @ORM\Column(name="calories", type="integer", nullable=true)
     * @Assert\NotBlank(message="Fill this field!")
     */
    private $calories;

    /**
     * @var string
     *
     * @Groups("post:read")
     * @ORM\Column(name="img_url", type="string", length=255, nullable=false)
     */
    private $imgUrl = '';

    /**
     * @var bool
     *
     * @Groups("post:read")
     * @ORM\Column(name="is_deleted", type="boolean")
     */
    private $isDeleted = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime
     *
     * @Groups("post:read")
     * @ORM\Column(name="created_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $modifiedAt;

    /**
     * @var RecipeCategory
     *
     * @Groups("post:read")
     * @ORM\ManyToOne(targetEntity="RecipeCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id", referencedColumnName="cat_id")
     * })
     */
    private $cat;

    /**
     * @var User
     *
     * @Groups("post:read")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * @var RecipeLike
     * @ORM\OneToMany(targetEntity="RecipeLike", mappedBy="recipe")
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * @return int
     */
    public function getRecipeId(): int
    {
        return $this->recipeId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->recipeId;
    }

    /**
     * @param int $recipeId
     */
    public function setRecipeId(int $recipeId): void
    {
        $this->recipeId = $recipeId;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(?string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getSteps(): ?string
    {
        return $this->steps;
    }

    public function setSteps(?string $steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPersons(): ?int
    {
        return $this->persons;
    }

    public function setPersons(?int $persons): self
    {
        $this->persons = $persons;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(?int $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getCat(): ?RecipeCategory
    {
        return $this->cat;
    }


    public function setCat(?RecipeCategory $cat): self
    {
        $this->cat = $cat;
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


    /**
     * @return Collection|RecipeLike[]
     */
    public function getLikes() : Collection
    {
        return $this->likes;
    }

    public function addLike(RecipeLike $like) : void
    {
        if(!$this->likes->contains($like)){
            $this->likes[] = $like;
            $like->setRecipe($this);
        }
    }

    public function removeLike(RecipeLike $like) : self
    {
        $this->likes->removeElement($like);
        return $this;
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user) : bool
    {
        foreach ($this->likes as $like ){
            if($like->getUser() === $user)
                return true;
        }
        return false;
    }

    private $count;

    /**
     * @return mixed
     * @Groups("post:read")
     */
    public function getCount()
    {
        return $this->getLikes()->count();
    }

    /**
     * @param mixed $count
     */
    public function setCount($count): void
    {
        $this->count = $count;
    }


}
