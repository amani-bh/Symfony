<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskRate
 *
 * @ORM\Table(name="task_rate")
 * @ORM\Entity
 */
class TaskRate
{
    /**
     * @var Rate
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity=Rate::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rate_id", referencedColumnName="rate_id")
     * })
     */
    private $rate;

    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity=Task::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_id", referencedColumnName="task_id")
     * })
     */
    private $task;

    /**
     * @return Rate
     */
    public function getRate(): Rate
    {
        return $this->rate;
    }

    /**
     * @param Rate $rate
     */
    public function setRate(Rate $rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @param Task $task
     */
    public function setTask(Task $task): void
    {
        $this->task = $task;
    }


}
