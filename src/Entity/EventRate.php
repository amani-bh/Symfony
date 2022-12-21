<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventRate
 *
 * @ORM\Table(name="event_rate")
 * @ORM\Entity
 */
class EventRate
{
    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity=Event::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     * })
     */
    private $event;

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
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

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


}
