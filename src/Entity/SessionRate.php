<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SessionRate
 *
 * @ORM\Table(name="session_rate")
 * @ORM\Entity
 */
class SessionRate
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
     * @var Session
     *
     * @ORM\ManyToOne(targetEntity=Session::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="session_id", referencedColumnName="session_id")
     * })
     */
    private $session;

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
