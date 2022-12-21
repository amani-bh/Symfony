<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Session
 *
 * @ORM\Table(name="session", indexes={@ORM\Index(name="FK_User_Session1", columns={"user_id"}), @ORM\Index(name="FK_User_Session", columns={"therp_id"})})
 * @ORM\Entity
 */
class Session
{
    public $className='Session';

    /**
     * @var int
     * @Groups("post:read")
     * @ORM\Column(name="session_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $sessionId;

    /**
     * @var string
     * @Groups("post:read")
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     */
    private $title ;

    /**
     * @var string
     * @Groups("post:read")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     */
    private $description ;

    /**
     * @var int
     * @Groups("post:read")
     * @ORM\Column(name="num_of_days", type="integer", nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     */
    private $numOfDays ;

    /**
     * @var int|null
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var bool
     * @Groups("post:read")
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     */
    private $isDeleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt ;

    /**
     * @var \DateTime
     * @Groups("post:read")
     * @ORM\Column(name="modified_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $modifiedAt;

    /**
     * @var \User
     * @Groups("post:read")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="therp_id", referencedColumnName="user_id")
     * })
     */
    private $therp;

    /**
     * @var \User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;
    /**
     * @var int

     */
    private  $nbr ;

    public function getSessionId(): ?int
    {
        return $this->sessionId;
    }
    public function getId(): ?int
    {
        return $this->sessionId;
    }

    //public function getId(): ?int
    //{
     //   return $this->sessionId;
   // }

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

    public function getNumOfDays(): ?int
    {
        return $this->numOfDays;
    }

    public function setNumOfDays(int $numOfDays): self
    {
        $this->numOfDays = $numOfDays;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

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

    public function getTherp(): ?User
    {
        return $this->therp;
    }

    public function setTherp(?User $therp): self
    {
        $this->therp = $therp;

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
     * @return int
     */
    public function getNbr(): int
    {
        return $this->nbr;
    }

    /**
     * @param int $nbr
     */
    public function setNbr(int $nbr): void
    {
        $this->nbr = $nbr;
    }



}

