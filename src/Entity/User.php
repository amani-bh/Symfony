<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 */

class User implements UserInterface
{
    /**
     * @var int
     *
     * @Groups("post:read")
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups("post:read")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups("post:read")
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups("post:read")
     */
    private $password2;


    /**
     * @return mixed
     */
    public function getPassword2()
    {
        return $this->password2;
    }

    /**
     * @param mixed $password2
     */
    public function setPassword2($password2): void
    {
        $this->password2 = $password2;
    }


    protected $captchaCode;


    /**
     * @return mixed
     */
    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    /**
     * @param mixed $captchaCode
     */
    public function setCaptchaCode($captchaCode): void
    {
        $this->captchaCode = $captchaCode;
    }


    /**
     * @Groups("post:read")
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $firstName;

    /**
     * @Groups("post:read")
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(name="balance", type="float", precision=10, scale=0, nullable=true)
     */
    private $balance;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_limited", type="boolean")
     */
    private $isLimited;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="limited_at", type="datetime", nullable=true)
     */
    private $limitedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="is_deleted", type="integer")
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
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Badge", inversedBy="user")
     * @ORM\JoinTable(name="user_badge",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="badge_id", referencedColumnName="badge_id")
     *   }
     * )
     */
    private $badge;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="user")
     * @ORM\JoinTable(name="user_event",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     *   }
     * )
     */
    private $event;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="user")
     * @ORM\JoinTable(name="user_role",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     *   }
     * )
     */
    private $role;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Task", inversedBy="user")
     * @ORM\JoinTable(name="user_task",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="task_id", referencedColumnName="task_id")
     *   }
     * )
     */
    private $task;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->badge = new \Doctrine\Common\Collections\ArrayCollection();
        $this->chat = new \Doctrine\Common\Collections\ArrayCollection();
        $this->event = new \Doctrine\Common\Collections\ArrayCollection();
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
        $this->task = new \Doctrine\Common\Collections\ArrayCollection();
    }

  
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getDateOfBirth(): ?\DateTime
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTime $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }
    
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function setScore(float $score): void
    {
        $this->score = $score;
    }

    public function setIsLimited(bool $isLimited): void
    {
        $this->isLimited = $isLimited;
    }


    public function setIsDeleted(int $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function getIsLimited(): ?bool
    {
        return $this->isLimited;
    }

    public function getLimitedAt(): ?\DateTimeInterface
    {
        return $this->limitedAt;
    }

    public function setLimitedAt(?\DateTimeInterface $limitedAt): self
    {
        $this->limitedAt = $limitedAt;

        return $this;
    }

    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Badge[]
     */
    public function getBadge(): Collection
    {
        return $this->badge;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badge->contains($badge)) {
            $this->badge[] = $badge;
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        $this->badge->removeElement($badge);

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event[] = $event;
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        $this->event->removeElement($event);

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->role->removeElement($role);

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTask(): Collection
    {
        return $this->task;
    }

    public function addTask(Task $task): self
    {
        if (!$this->task->contains($task)) {
            $this->task[] = $task;
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        $this->task->removeElement($task);

        return $this;
    }
    

       //------generate interface methods-------------------------
    
     /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $array=array();
        foreach($this->getRole() as $i => $role) {
            array_push($array, $role->getRoleName() );
        }
        return $array;
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    //---------------------------------------------------------


}
