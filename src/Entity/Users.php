<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $apiToken;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="User")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="CommentMusic", mappedBy="User")
     */
    private $commentMusics;

    /**
     * @ORM\OneToMany(targetEntity="Music", mappedBy="User")
     */
    private $musics;

    /**
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="users_friendasks")
     */
    private $users_friends;

    /**
     * @ORM\ManyToMany(targetEntity="Users", inversedBy="users_friends")
     * @ORM\JoinTable(name="users_friendask",
     *     joinColumns={@ORM\JoinColumn(name="users_child_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="users_friendask_id", referencedColumnName="id")}
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $users_friendasks;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->musics = new ArrayCollection();
        $this->commentMusics = new ArrayCollection();
        $this->users_friendasks = new ArrayCollection();
        $this->users_friends = new ArrayCollection();
    }


    /**
     * Add users_friend.
     *
     * @param Users $users_friend
     *
     * @return Users
     */
    public function addUsersFriend(Users $users_friend)
    {
        if($this->users_friends->contains($users_friend))
            return $this;
        $this->users_friends[] = $users_friend;
        $users_friend->addUsersFriendask($this);
        return $this;
    }

    /**
     * Remove users_friend
     *
     * @param Users $users_friend
     * @return Users
     */
    public function removeUsersFriend(Users $users_friend)
    {
        if(!$this->users_friends->contains($users_friend))
            return $this;
        $this->users_friends->removeElement($users_friend);
        $users_friend->removeUsersFriendask($this);
        return $this;
    }

    /**
     * Add users_friend.
     *
     * @param Users $users_friendasks
     *
     * @return Users
     */
    public function addUsersFriendask(Users $users_friendask)
    {
        if($this->users_friendasks->contains($users_friendask))
            return $this;
        $this->users_friendasks[] = $users_friendask;
        $users_friendask->addUsersFriend($this);
        return $this;
    }

    /**
     * Remove users_friendask
     *
     * @param Users $users_friendask
     * @return Users
     */
    public function removeUsersFriendask(Users $users_friendask)
    {
        if(!$this->users_friendasks->contains($users_friendask))
            return $this;
        $this->users_friendasks->removeElement($users_friendask);
        $users_friendask->removeUsersFriend($this);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getUsersFriendasks(): Collection
    {
        return $this->users_friendasks;
    }

    /**
     * @param Collection $users_friendasks
     * @return Users
     */
    public function setUsersFriendask(Collection $users_friendasks): self
    {
        $this->users_friendasks = $users_friendasks;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsersFriends()
    {
        return $this->users_friends;
    }

    /**
     * @param mixed users_friends
     * @return Users
     */
    public function setUsersFriends($users_friends): self
    {
        $this->users_friends = $users_friends;
        return $this;
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @return Collection|Music[]
     */
    public function getMusics(): Collection
    {
        return $this->musics;
    }

    // addProduct() and removeProduct() were also added

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
