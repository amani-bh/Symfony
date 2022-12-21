<?php

namespace App\Entity;

use App\Repository\ReportNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="report_notification")
 * @ORM\Entity(repositoryClass=ReportNotificationRepository::class)
 */
class ReportNotification implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="notification_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $notificationId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * @var Report
     *
     * @ORM\ManyToOne(targetEntity=Report::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="report_id", referencedColumnName="report_id")
     * })
     */
    private $report;

    /**
     * @var bool
     *
     * @ORM\Column(name="seen_by_admin", type="boolean", nullable=false, options={"default"="0"})
     */
    private $seenByAdmin = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="seen_by_user", type="boolean", nullable=false, options={"default"="0"})
     */
    private $seenByUser = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="closed", type="boolean", nullable=false, options={"default"="0"})
     */
    private $closed = '0';

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
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity=Event::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     * })
     */
    private $event;

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
     * @var Session
     *
     * @ORM\ManyToOne(targetEntity=Session::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="session_id", referencedColumnName="session_id")
     * })
     */
    private $session;

    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity=Task::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="task_id")
     * })
     */
    private $task;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->notificationId;
    }

    /**
     * @param int $notificationId
     */
    public function setNotificationId(int $notificationId): void
    {
        $this->notificationId = $notificationId;
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

    /**
     * @return bool
     */
    public function isSeenByAdmin()
    {
        return $this->seenByAdmin;
    }

    /**
     * @param bool $seenByAdmin
     */
    public function setSeenByAdmin($seenByAdmin): void
    {
        $this->seenByAdmin = $seenByAdmin;
    }

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
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event): void
    {
        $this->event = $event;
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

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @param Task $task
     */
    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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

    /**
     * @return bool
     */
    public function isSeenByUser()
    {
        return $this->seenByUser;
    }

    /**
     * @param bool $seenByUser
     */
    public function setSeenByUser($seenByUser): void
    {
        $this->seenByUser = $seenByUser;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * @param bool $closed
     */
    public function setClosed($closed): void
    {
        $this->closed = $closed;
    }


    public function jsonSerialize()
    {
        return [
            "report" =>$this->getReport()->getNote(),
            "user" => $this->getUser()->getFirstName(),
            "date"=> $this->getCreatedAt()->getTimestamp()
        ];
    }

    public function getNotificationId(): ?int
    {
        return $this->notificationId;
    }

    public function getSeenByAdmin(): ?bool
    {
        return $this->seenByAdmin;
    }

    public function getSeenByUser(): ?bool
    {
        return $this->seenByUser;
    }

    public function getClosed(): ?bool
    {
        return $this->closed;
    }
}
