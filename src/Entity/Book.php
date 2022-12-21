<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Book
 *
 * @ORM\Table(name="book", indexes={@ORM\Index(name="FK_User_Book", columns={"user_id"}), @ORM\Index(name="FK_Category_Book", columns={"cat_id"})})
 *
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    public $className='Book';

    /**
     * @var int
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="book_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $bookId;

    /**
     * @var string
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="img_url", type="string", length=255, nullable=false)
     */
    private $imgUrl = '';

    /**
     * @var string
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="file_path", type="string", length=255, nullable=false)
     */
    private $filePath = '';

    /**
     * @var string
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="author", type="string", length=255, nullable=false)
     */
    private $author = '';

    /**
     * @var string
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description = '';

    /**
     * @var int
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="views", type="integer", nullable=false)
     */
    private $views = '0';

    /**
     * @var bool
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     */
    private $isDeleted = '0';

    /**
     * @var \DateTime|null
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt ;

    /**
     * @var \DateTime
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\Column(name="modified_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $modifiedAt ;

    /**
     * @var BookCategory
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\ManyToOne(targetEntity=BookCategory::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id", referencedColumnName="cat_id")
     * })
     */
    private $cat;

    /**
     * @var User
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * @return int
     */
    public function getBookId(): int
    {
        return $this->bookId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->bookId;
    }

    /**
     * @param int $bookId
     */
    public function setBookId(int $bookId): void
    {
        $this->bookId = $bookId;
    }

    /**
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * @param string $imgUrl
     */
    public function setImgUrl( $imgUrl)
    {
        $this->imgUrl = $imgUrl;
    }
    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     */
    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime|null $deletedAt
     */
    public function setDeletedAt(?\DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
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
     * @return \DateTime
     */
    public function getModifiedAt(): \DateTime
    {
        return $this->modifiedAt;
    }

    /**
     * @param \DateTime $modifiedAt
     */
    public function setModifiedAt(\DateTime $modifiedAt): void
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return BookCategory
     */
    public function getCat(): BookCategory
    {
        return $this->cat;
    }

    /**
     * @param BookCategory $cat
     */
    public function setCat(BookCategory $cat): void
    {
        $this->cat = $cat;
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

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }




}