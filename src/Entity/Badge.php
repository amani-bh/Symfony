<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Badge
 *
 * @ORM\Table(name="badge")
 * @ORM\Entity
 */
class Badge
{
    /**
     * @var int
     *
     * @ORM\Column(name="badge_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $badgeId;

    /**
     * @var string
     *
     * @ORM\Column(name="badge_name", type="string", length=255, nullable=false)
     */
    private $badgeName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=255, nullable=false)
     */
    private $imgUrl = '';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="badge")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getBadgeId(): ?int
    {
        return $this->badgeId;
    }

    public function getBadgeName(): ?string
    {
        return $this->badgeName;
    }

    public function setBadgeName(string $badgeName): self
    {
        $this->badgeName = $badgeName;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

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
            $user->addBadge($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            $user->removeBadge($this);
        }

        return $this;
    }

}
