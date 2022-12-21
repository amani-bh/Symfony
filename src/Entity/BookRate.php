<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookRate
 *
 * @ORM\Table(name="book_rate")
 * @ORM\Entity
 */
class BookRate
{
    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity=Book::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="book_id", referencedColumnName="book_id")
     * })
     */
    private $book;

    /**
     * @var Rate
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity=Rate::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rate_id", referencedColumnName="rate_id")
     * })
     */
    private $rate;

    /**
     * @return Book
     */
    public function getBook(): Book
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook(Book $book): void
    {
        $this->book = $book;
    }

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


}
