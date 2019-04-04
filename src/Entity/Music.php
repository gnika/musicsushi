<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MusicRepository")
 */
class Music
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     *
     * @var Users
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="musics")
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\File(
     *     maxSize = "15M",
     *     mimeTypes = {"audio/mpeg"},
     *     mimeTypesMessage = "Veuillez ajouter un type de fichier valide"
     * )
     */
    private $file;

    // Notice the "cascade" option below, this is mandatory if you want Doctrine to automatically persist the related entity
    /**
     * @ORM\OneToMany(targetEntity="CommentMusic", mappedBy="music", cascade={"persist"})
     */
    public $commentMusics;

    public function __construct()
    {
        $this->commentMusics = new ArrayCollection(); // Initialize $offers as an Doctrine collection
    }

    // Adding both an adder and a remover as well as updating the reverse relation are mandatory
    // if you want Doctrine to automatically update and persist (thanks to the "cascade" option) the related entity
    public function addCommentMusic(CommentMusic $commentMusic): void
    {
        $commentMusic->music = $this;
        $this->commentMusics->add($commentMusic);
    }

    public function removeCommentMusic(CommentMusic $commentMusic): void
    {
        $commentMusic->music = null;
        $this->commentMusics->removeElement($commentMusic);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param mixed $User
     */
    public function setUser($User)
    {
        $this->User = $User;
    }
}
