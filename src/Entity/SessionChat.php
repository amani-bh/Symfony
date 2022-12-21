<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SessionChat
 *
 * @ORM\Table(name="session_chat")
 * @ORM\Entity
 */
class SessionChat
{
    /**
     * @var int
     *
     * @ORM\Column(name="chat_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $chatId;

    /**
     * @var \Session
     *
     * @ORM\ManyToOne(targetEntity="Session")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="session_id", referencedColumnName="session_id")
     * })
     */
    private $session;

    public function getChatId(): ?int
    {
        return $this->chatId;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }


}
