<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SessionReport
 *
 * @ORM\Table(name="session_report")
 * @ORM\Entity
 */
class SessionReport
{
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
     * @var Session
     *
     * @ORM\ManyToOne(targetEntity=Session::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="session_id", referencedColumnName="session_id")
     * })
     */
    private $session;

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

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }


}
