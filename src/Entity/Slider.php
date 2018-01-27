<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SliderRepository")
 */
class Slider
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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\SliderImage", cascade={"persist"})
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
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
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param SliderImage $image
     */
    public function addImage(SliderImage $image): void
    {
        $this->images->add($image);
    }

    /**
     * @param SliderImage $image
     */
    public function removeImage(SliderImage $image): void
    {
        $this->images->removeElement($image);
    }

    /**
     * After attaching a new image, we need to update images positions
     */
    public function updateImagesPositions(): void
    {
        $slides = $this->getImages()->toArray();

        $slides = array_values($slides);

        /*
         * @var SliderImage
         */
        foreach ($slides as $index => $slide) {
            $slide->setPosition($index + 1);
        }
    }
}
