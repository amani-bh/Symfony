<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Therapist
 *
 * @ORM\Table(name="therapist")
 * @ORM\Entity
 */
class Therapist
{
    /**
     * @var string
     *
     * @ORM\Column(name="speciality", type="string", length=255, nullable=false)
     */
    private $speciality = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="can_create_session", type="boolean", nullable=false, options={"default"="1"})
     */
    private $canCreateSession = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_create_task", type="boolean", nullable=false, options={"default"="1"})
     */
    private $canCreateTask = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_upload_book", type="boolean", nullable=false, options={"default"="1"})
     */
    private $canUploadBook = true;

    /**
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getCanCreateSession(): ?bool
    {
        return $this->canCreateSession;
    }

    public function setCanCreateSession(bool $canCreateSession): self
    {
        $this->canCreateSession = $canCreateSession;

        return $this;
    }

    public function getCanCreateTask(): ?bool
    {
        return $this->canCreateTask;
    }

    public function setCanCreateTask(bool $canCreateTask): self
    {
        $this->canCreateTask = $canCreateTask;

        return $this;
    }

    public function getCanUploadBook(): ?bool
    {
        return $this->canUploadBook;
    }

    public function setCanUploadBook(bool $canUploadBook): self
    {
        $this->canUploadBook = $canUploadBook;

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


}
