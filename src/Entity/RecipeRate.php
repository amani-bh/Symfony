<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecipeRate
 *
 * @ORM\Table(name="recipe_rate")
 * @ORM\Entity
 */
class RecipeRate
{
    /**
     * @var Rate
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity=Rate::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rate_id", referencedColumnName="rate_id")
     * })
     */
    private $rate;

    /**
     * @var Recipe
     *
     * @ORM\ManyToOne(targetEntity=Recipe::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recipe_id", referencedColumnName="recipe_id")
     * })
     */
    private $recipe;

    /**
     * @return Rate
     */
    public function getRate(): Rate
    {
        return $this->rate;
    }

    /**
     * @param Rate $rate
     */
    public function setRate(Rate $rate): void
    {
        $this->rate = $rate;
    }

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


}
