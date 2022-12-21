<?php

namespace App\Entity;

use App\Repository\RecipeLikeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="recipe_like", indexes={@ORM\Index(name="FK_Recipe", columns={"recipe_id"}), @ORM\Index(name="FK_User_Like", columns={"user_id"})})
 * @ORM\Entity(repositoryClass=RecipeLikeRepository::class)
 */
class RecipeLike
{
    /**
     * @ORM\Id
     * @Groups("post:read")
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @var Recipe
     *@Groups("post:read")
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="likes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recipe_id", referencedColumnName="recipe_id")
     * })
     */
    private $recipe;

    /**
     * @var User
     *@Groups("post:read")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="likes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * @return Recipe
     */
    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    /**
     * @param Recipe $recipe
     */
    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}
