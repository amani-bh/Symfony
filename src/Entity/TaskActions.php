<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * TaskActions
 *
 * @ORM\Table(name="task_actions", indexes={@ORM\Index(name="FK_Task_TaskAction", columns={"task_id"})})
 * @ORM\Entity
 */
class TaskActions
{
    /**
     * @var int
     *
     * @ORM\Column(name="action_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $actionId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     * @Groups("post:read")
     */
    private $title ;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="champ obligatoire")
     * @Groups("post:read")
     */
    private $description ;
    /**
     * @var bool
     *
     * @ORM\Column(name="done", type="boolean", nullable=false)
     */
    private $done = '0';

    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="Task")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="task_id")
     * })
     * @Groups("post:read")
     */
    private $task;
    public function getActionId(): ?int
    {
        return $this->actionId;
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

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }
    public function getDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }

}
