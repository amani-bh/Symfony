<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookReport
 *
 * @ORM\Table(name="book_report")
 * @ORM\Entity
 */
class BookReport
{
    /**
     * @var \Book
     * @ORM\ManyToOne(targetEntity=Book::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="book_id", referencedColumnName="book_id")
     * })
     */
    private $book;

    /**
     * @var Report
     * @ORM\Id
     * @ORM\OneToOne(targetEntity=Report::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="report_id", referencedColumnName="report_id")
     * })
     */
    private $report;

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
     * @return Report
     */
    public function getReport(): Report
    {
        return $this->report;
    }

    /**
     * @param Report $report
     */
    public function setReport(Report $report): void
    {
        $this->report = $report;
    }


}
