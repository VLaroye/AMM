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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, name="facebooklink")
     * @Assert\Url()
     */
    private $facebookLink;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * @param mixed $facebookLink
     */
    public function setFacebookLink($facebookLink): void
    {
        $this->facebookLink = $facebookLink;
    }


}
