<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SessionMessage
 *
 * @ORM\Table(name="session_message")
 * @ORM\Entity
 */
class SessionMessage
{
    /**
     * @var int
     *
     * @ORM\Column(name="msg_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $msgId;

    /**
     * @var string
     *
     * @ORM\Column(name="msg", type="string", length=255, nullable=false)
     */
    private $msg = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \SessionChat
     *
     * @ORM\ManyToOne(targetEntity="SessionChat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chat_id", referencedColumnName="chat_id")
     * })
     */
    private $chat;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    public function getMsgId(): ?int
    {
        return $this->msgId;
    }

    public function getMsg(): ?string
    {
        return $this->msg;
    }

    public function setMsg(string $msg): self
    {
        $this->msg = $msg;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getChat(): ?SessionChat
    {
        return $this->chat;
    }

    public function setChat(?SessionChat $chat): self
    {
        $this->chat = $chat;

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
