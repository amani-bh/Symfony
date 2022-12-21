<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Report
 *
 * @ORM\Table(name="report")
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 */
class Report
{

    /**
     * @var int
     *
     * @ORM\Column(name="report_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reportId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type = '';

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Cannot report without note")
     * @Assert\Length(min="5", max="254", minMessage="This note is too short. It should have 5 characters or more.")
     */
    private $note;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_closed", type="boolean", nullable=false)
     */
    private $isClosed = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="closed_at", type="datetime", nullable=true)
     */
    private $closedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reporter_id", referencedColumnName="user_id")
     * })
     */
    private $reporter;

    /**
     * @return int
     */
    public function getReportId(): int
    {
        return $this->reportId;
    }

    /**
     * @param int $reportId
     */
    public function setReportId(int $reportId): void
    {
        $this->reportId = $reportId;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return ?string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->isClosed;
    }

    /**
     * @param bool $isClosed
     */
    public function setIsClosed($isClosed): void
    {
        $this->isClosed = $isClosed;
    }

    /**
     * @return \DateTime|null
     */
    public function getClosedAt(): ?\DateTime
    {
        return $this->closedAt;
    }

    /**
     * @param \DateTime|null $closedAt
     */
    public function setClosedAt(?\DateTime $closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return User
     */
    public function getReporter(): User
    {
        return $this->reporter;
    }

    /**
     * @param User $reporter
     */
    public function setReporter(User $reporter): void
    {
        $this->reporter = $reporter;
    }

    public function getIsClosed(): ?bool
    {
        return $this->isClosed;
    }


}
