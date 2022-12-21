<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * PaidTask
 *
 * @ORM\Table(name="paid_task")
 * @ORM\Entity
 */
class PaidTask
{
    public $className='PaidTask';
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     * @Groups("BS")
     * @Groups("post:read")
     */
    private $price ;

    /**
     * @var Task
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity=Task::class)
     * @Groups("BS")
     * @Groups("post:read")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="task_id")
     * })
     */
    private $task;

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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



}
