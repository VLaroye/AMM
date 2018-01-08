<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Assert\Time()
     */
    private $beginningTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", nullable=true)
     *
     * @Assert\Time()
     * @Assert\GreaterThanOrEqual(propertyPath="beginningTime")
     */
    private $endingTime;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $location;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type(type="integer")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Url()
     */
    private $facebookLink;

    /**
     * @var EventCategory
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\EventCategory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

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
     * @return \Datetime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \Datetime $date
     */
    public function setDate(?\Datetime $date): void
    {
        $this->date = $date;
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
    public function getBeginningTime(): ?\DateTime
    {
        return $this->beginningTime;
    }

    /**
     * @param \DateTime $beginningTime
     */
    public function setBeginningTime(?\DateTime $beginningTime): void
    {
        $this->beginningTime = $beginningTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndingTime(): ?\DateTime
    {
        return $this->endingTime;
    }

    /**
     * @param \DateTime $endingTime
     */
    public function setEndingTime(?\DateTime $endingTime): void
    {
        $this->endingTime = $endingTime;
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
}
