<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventReport
 *
 * @ORM\Table(name="event_report")
 * @ORM\Entity
 */
class EventReport
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
     * @var Report
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity=Report::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="report_id", referencedColumnName="report_id")
     * })
     */
    private $report;

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
     * @return Report
     */
    public function getReport(): Report
    {
        return $this->report;
    }

    /**
     * @param Report $report
     */
    public function setReport(Report $report): void
    {
        $this->report = $report;
    }


}
