<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArtistRepository")
 */
class Artist
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
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(
     *     groups={"default"},
     *     message="Ce champ doit être renseigné"
     * )
     * @Assert\Length(
     *     groups={"default"},
     *     max=25,
     *     maxMessage="{{ limit }} caractères maximum"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $slugifiedName;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(
     *     groups={"default"},
     *     message="Ce champ doit être renseigné"
     * )
     * @Assert\Length(
     *     groups={"default"},
     *     max= 600,
     *     maxMessage="Ce champ est limité à {{ limit }} caractères."
     * )
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(
     *     groups={"default"},
     *     message="Ce champ doit être renseigné"
     * )
     */
    private $style;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(
     *     groups={"default"},
     *     message="Ce champ doit être renseigné"
     * )
     * @Assert\Length(
     *     groups={"default"},
     *     max=20,
     *     maxMessage="{{ limit }} caractères maximum"
     * )
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Url(
     *     groups={"default"},
     *     message="L'adresse '{{ value }}' n'est pas une adresse valide."
     * )
     */
    private $youtubeLink;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Url(
     *     groups={"default"},
     *     message="L'adresse '{{ value }}' n'est pas une adresse valide."
     * )
     */
    private $facebookLink;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Url(
     *     groups={"default"},
     *     message="L'adresse '{{ value }}' n'est pas une adresse valide."
     * )
     */
    private $soundcloudLink;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Url(
     *     groups={"default"},
     *     message="L'adresse '{{ value }}' n'est pas une adresse valide."
     * )
     */
    private $bandcampLink;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(
     *     groups={"default"},
     *     message="Ce champ doit être renseigné"
     * )
     * @Assert\Type(
     *     groups={"default"},
     *     type="integer",
     *     message="Cette valeur doit être un nombre entier"
     * )
     */
    private $priority;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $image;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getStyle(): ?string
    {
        return $this->style;
    }

    /**
     * @param string $style
     */
    public function setStyle(string $style): void
    {
        $this->style = $style;
    }

    /**
     * @return string
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     */
    public function setOrigin(string $origin): void
    {
        $this->origin = $origin;
    }

    /**
     * @return string
     */
    public function getYoutubeLink(): ?string
    {
        return $this->youtubeLink;
    }

    /**
     * @param string $youtubeLink
     */
    public function setYoutubeLink(string $youtubeLink): void
    {
        $this->youtubeLink = $youtubeLink;
    }

    /**
     * @return int
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
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
     * @return string
     */
    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    /**
     * @param string $facebookLink
     */
    public function setFacebookLink(string $facebookLink)
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @return string
     */
    public function getSoundcloudLink(): ?string
    {
        return $this->soundcloudLink;
    }

    /**
     * @param string $soundcloudLink
     */
    public function setSoundcloudLink(string $soundcloudLink)
    {
        $this->soundcloudLink = $soundcloudLink;
    }

    /**
     * @return string
     */
    public function getBandcampLink(): ?string
    {
        return $this->bandcampLink;
    }

    /**
     * @param string $bandcampLink
     */
    public function setBandcampLink(string $bandcampLink)
    {
        $this->bandcampLink = $bandcampLink;
    }

    /**
     * @return string
     */
    public function getSlugifiedName(): ?string
    {
        return $this->slugifiedName;
    }

    /**
     * @param string $slugifiedName
     */
    public function setSlugifiedName(string $slugifiedName): void
    {
        $this->slugifiedName = $slugifiedName;
    }
}
