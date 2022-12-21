<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    public $className='Event';
    /**
     * @var int
     *
     * @ORM\Column(name="event_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *   @Groups("BS")
     * @Groups("post:read")
     */
    private $eventId;

    /**
     * @var string
     * @Groups("BS")
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     * @Groups("post:read")
     */
    private $title ;

    /**
     * @var string
     *
     * @Groups("BS")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     * @Groups("post:read")
     */
    private $description ;

    /**
     * @var \DateTime|null
     *
     *  @Groups("BS")
     * @ORM\Column(name="start_date", type="date", nullable=true)
     * @Assert\NotBlank(message="champ obligatoire")
     * @Assert\GreaterThan("today")
     * @Groups("post:read")
     */

    private $startDate;

    /**
     * @var \DateTime|null
     *
     *  @Groups("BS")
     * @ORM\Column(name="end_date", type="date", nullable=true)
     * @Assert\NotBlank(message="champ obligatoire")
     * @Assert\GreaterThan(propertyPath="startDate")
     * @Groups("post:read")
     */
    private $endDate;

    /**
     * @var int
     *
     * @ORM\Column(name="min_users", type="integer", nullable=false)
     */
    private $minUsers = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="max_users", type="integer", nullable=false)
     */
    private $maxUsers = '0';

    /**
     * @var string
     *
     * @Groups("post:read")
     * @ORM\Column(name="location", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     */
    private $location ;

    /**
     * @var string|null
     *  @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var float
     *  @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price ;

    /**
     * @var string
     *
     * @Groups("post:read")
     * @ORM\Column(name="img_url", type="string", length=255, nullable=false)
     */
    private $imgUrl ;

    /**
     * @var bool
     *
     * @Groups("post:read")
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
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
     * @ORM\Column(name="created_at", type="datetime",options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime",options={"default"="CURRENT_TIMESTAMP"})
     */
    private $modifiedAt;

    /**
     * @var EventCategory
     *
     * @Groups("post:read")
     * @ORM\ManyToOne(targetEntity="EventCategory")
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
     *   @ORM\JoinColumn(name="u_id", referencedColumnName="user_id")
     * })
     */
    private $u;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @Groups("post:read")
     * @ORM\ManyToMany(targetEntity="User", mappedBy="event")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
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

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getMinUsers(): ?int
    {
        return $this->minUsers;
    }

    public function setMinUsers(int $minUsers): self
    {
        $this->minUsers = $minUsers;

        return $this;
    }

    public function getMaxUsers(): ?int
    {
        return $this->maxUsers;
    }

    public function setMaxUsers(int $maxUsers): self
    {
        $this->maxUsers = $maxUsers;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    public function setImgUrl( $imgUrl)
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

    public function getCat(): ?EventCategory
    {
        return $this->cat;
    }

    public function setCat(?EventCategory $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

    public function getU(): ?User
    {
        return $this->u;
    }

    public function setU(?User $u): self
    {
        $this->u = $u;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->addEvent($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            $user->removeEvent($this);
        }

        return $this;
    }
    public function getId(): int
    {
        return $this->eventId;
    }

}
