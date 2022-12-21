<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Task
 *
 * @ORM\Table(name="task", indexes={@ORM\Index(name="FK_User_Task", columns={"u_id"}), @ORM\Index(name="FK_Category_Task", columns={"cat_id"})})
 * @ORM\Entity
 */
class Task
{
    public $className='Task';
    /**
     * @var int
     *
     * @ORM\Column(name="task_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $taskId;

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=255, nullable=false)
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $imgUrl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     *   @Groups("BS")
     * @Groups("post:read")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $description ;

    /**
     * @var int
     *
     * @ORM\Column(name="num_of_days", type="integer", nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $numOfDays ;
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=25, nullable=true)
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="min_users", type="integer", nullable=false)
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $minUsers = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="max_users", type="integer", nullable=false)
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $maxUsers = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $isDeleted = '0';

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $deletedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $createdAt ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     *  @Groups("BS")
     * @Groups("post:read")
     */
    private $modifiedAt ;

    /**
     * @var TaskCategory
     *
     * @ORM\ManyToOne(targetEntity="TaskCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id", referencedColumnName="cat_id")
     * })
     * @Groups("post:read")
     */
    private $cat;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @Groups("post:read")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="u_id", referencedColumnName="user_id")
     * })
     */
    private $u;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="task")
     */
    private $user;
    /**
     * @var float

     */
    private  $price;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->taskId;
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

    /**
     * @return string
     */
    public function getType(): ?string
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

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt->format('d/m/Y');
    }

    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime('@'.strtotime('now'));

        return $this;
    }

    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTime $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getCat(): ?TaskCategory
    {
        return $this->cat;
    }

    public function setCat(?TaskCategory $cat): self
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


    public function getUser(): ?User
    {
        return $this->u;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->addTask($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            $user->removeTask($this);
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getTaskId(): ?int
    {
        return $this->taskId;
    }


}
