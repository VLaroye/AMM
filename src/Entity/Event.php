<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank(
     *     groups={"default"},
     *     message="Ce champ doit être renseigné."
     * )
     * @Assert\Length(
     *     groups={"default"},
     *     max=20,
     *     maxMessage="{{ limit }} caractères maximum."
     * )
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotBlank(
     *     groups={"default"},
     *     message="Ce champ doit être renseigné."
     * )
     * @Assert\DateTime(
     *     groups={"default"},
     *     message="Ceci n'est pas une date valide."
     * )
     */
    private $beginningDateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime(
     *     groups={"default"},
     *     message="Ceci n'est pas une date valide"
     * )
     */
    private $endingDateTime;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=50)
     *
     * @Assert\Length(
     *     groups={"default"},
     *     max=50,
     *     maxMessage="{{ limit }} caractères maximum."
     * )
     */
    private $location;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type(
     *     groups={"default"},
     *     type="integer",
     *     message="Le prix doit être un nombre"
     * )
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=500)
     *
     * @Assert\NotBlank(
     *     groups={"default"},
     *     message="Ce champ doit être renseigné"
     * )
     * @Assert\Length(
     *     groups={"default"},
     *     max=500,
     *     maxMessage="Limité à {{ limit }} caractères"
     * )
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Url(
     *     groups={"default"},
     *     message="Ceci n'est pas une URL valide"
     * )
     */
    private $facebookLink;

    /**
     * @var EventCategory
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\EventCategory")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Valid(
     *     groups={"default"},
     * )
     */
    private $category;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Valid(
     *     groups={"eventImage"}
     * )
     */
    private $image;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Valid(
     *     groups={"eventCoverImage"}
     * )
     */
    private $coverImage;

    public function __construct()
    {
        $this->beginningDateTime = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return int
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    /**
     * @param string $facebookLink
     */
    public function setFacebookLink(?string $facebookLink): void
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @return \DateTime
     */
    public function getBeginningDateTime(): ?\DateTime
    {
        return $this->beginningDateTime;
    }

    /**
     * @param \DateTime $beginningDateTime
     */
    public function setBeginningDateTime(?\DateTime $beginningDateTime): void
    {
        $this->beginningDateTime = $beginningDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndingDateTime(): ?\DateTime
    {
        return $this->endingDateTime;
    }

    /**
     * @param \DateTime $endingDateTime
     */
    public function setEndingDateTime(?\DateTime $endingDateTime): void
    {
        $this->endingDateTime = $endingDateTime;
    }

    /**
     * @return EventCategory
     */
    public function getCategory(): ?EventCategory
    {
        return $this->category;
    }

    /**
     * @param EventCategory $category
     */
    public function setCategory(EventCategory $category): void
    {
        $this->category = $category;
    }

    /**
     * @return Image
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image): void
    {
        $this->image = $image;
    }

    /**
     * @return Image
     */
    public function getCoverImage(): ?Image
    {
        return $this->coverImage;
    }

    /**
     * @param Image $coverImage
     */
    public function setCoverImage(Image $coverImage): void
    {
        $this->coverImage = $coverImage;
    }

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback(
     *     groups={"default"}
     * )
     */
    public function validateDates(ExecutionContextInterface $context)
    {
        if (!is_null($this->endingDateTime) && $this->endingDateTime < $this->beginningDateTime) {
            $context->buildViolation('La date de fin doit se trouver après la date de début !')
                ->atPath('endingDateTime')
                ->addViolation();
        }
    }
}
