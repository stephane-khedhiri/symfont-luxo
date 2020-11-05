<?php

namespace App\Entity;

use App\Repository\AnnouncementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnnouncementRepository::class)
 */
class Announcement
{

    CONST ACHAT = 0;
    const LOCATION = 1;
    const TYPES = [ 'studio', 'house', 'appartment', 'villa'];
    const CATEGORIES = ['buying', 'Rental'];
    const ENERGIES = ['gas', 'electric'];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="announcements")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $postedBy;

    /**
     * @var Image[]
     * @ORM\ManyToMany(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinTable(
     *     name="announcement_has_images",
     *     joinColumns={@ORM\JoinColumn(name="announcement_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $images;

    /**
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @Assert\Regex("/^[0-9]{5}$/")
     * @ORM\Column(type="string", length=255)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $category;

    /**
     * @Assert\Range(min = 10, max = 400)
     * @ORM\Column(type="integer")
     */
    private $area;

    /**
     * @ORM\Column(type="integer")
     */
    private $room;

    /**
     * @ORM\Column(type="integer")
     */
    private $bedroom;

    /**
     * @ORM\Column(type="integer")
     */
    private $energy;

    /**
     * @ORM\Column(type="integer")
     */
    private $floor;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->images = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(string $date): Announcement
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(int $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getBedroom(): ?int
    {
        return $this->bedroom;
    }

    public function setBedroom(int $bedroom): self
    {
        $this->bedroom = $bedroom;

        return $this;
    }

    public function getEnergy(): ?int
    {
        return $this->energy;
    }

    public function setEnergy(int $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * @return User
     */
    public function getPostedBy(): ?User
    {
        return $this->postedBy;
    }

    /**
     * @param User $user
     *
     * @return Announcement
     */
    public function setPostedBy(User $user): Announcement
    {
        $this->postedBy = $user->addAnnouncement($this);

        return $this;
    }

    /**
     * @return Image[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param Image $image
     *
     * @return Announcement
     */
    public function addImage(Image $image)
    {
        $this->images->add($image->setParent($this));

        return $this;
    }

    /**
     * @param array $images
     *
     * @return Announcement
     */
    public function setImages(array $images)
    {
        foreach ($images as $image) {
            $this->addImage($image);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     *
     * @return Announcement
     */
    public function setZipCode($zipCode): Announcement
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getEnergyToString()
    {
        return self::ENERGIES[$this->energy];
    }

    public function getTypeName()
    {
        return Announcement::TYPES[$this->getType()];
    }
}
