<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EventCategory
 *
 * @ORM\Table(name="event_category")
 * @ORM\Entity(repositoryClass="App\Repository\EventCategoryRepository")
 */
class EventCategory
{
    /**
     * @var int
     *
     * @Groups("post:read")
     * @ORM\Column(name="cat_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $catId;

    /**
     * @var string
     *
     * @Groups("post:read")
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     */
    private $name ;

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
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $modifiedAt ;
    /**
     * @var int

     */
    private  $totalEvents ;

    public function getCatId(): ?int
    {
        return $this->catId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalEvents(): ?int
    {
        return $this->totalEvents;
    }

    /**
     * @param int $totalEvents
     */
    public function setTotalEvents(int $totalEvents): void
    {
        $this->totalEvents = $totalEvents;
    }
}
