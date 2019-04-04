<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentMusicRepository")
 */
class CommentMusic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timeMusic;

    /**
     * @ORM\ManyToOne(targetEntity="Music", inversedBy="commentMusics")
     */
    public $music;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="commentMusics")
     */
    public $users;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getTimeMusic(): ?string
    {
        return $this->timeMusic;
    }

    public function setTimeMusic(string $timeMusic): self
    {
        $this->timeMusic = $timeMusic;

        return $this;
    }
}
