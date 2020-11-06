<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Announcement[]
     *@ORM\OneToMany(targetEntity="Announcement", mappedBy="postedBy", cascade={"remove"})
     */
    private $announcements;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="date")
     */
    private $birth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;



    public function __construct()
    {
        $this->announcements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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
     * @return DateTimeInterface
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * @param DateTimeInterface $birth
     *
     * @return User
     */
    public function setBirth($birth): User
    {
        $this->birth = $birth;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @return Announcement[]|ArrayCollection|PersistentCollection
     */
    public function getAnnouncements()
    {
        return $this->announcements;
    }

    /**
     * @param Announcement $announcement
     *
     * @return User
     */
    public function addAnnouncement(Announcement $announcement)
    {
        if(!$this->announcements->contains($announcement))
        {
            $this->announcements->add($announcement);
        }
        return $this;
    }

    /**
     * @param Announcement[] $announcements
     *
     * @return User
     */
    public function setAnnouncements(array $announcements)
    {
        foreach ($announcements as $announcement)
        {
            $this->addAnnouncement($announcement);
        }

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {

    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,

        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function __toString()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string|boolean $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}
