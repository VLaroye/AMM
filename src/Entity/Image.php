<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Image
 *
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    public function __construct()
    {
        $this->inGallery = false;
    }

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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $inGallery;

    /**
     * @return int
     */
    public function getId(): int
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
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFilename(string $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * @return bool
     */
    public function isInGallery(): bool
    {
        return $this->inGallery;
    }

    /**
     * @param bool $inGallery
     */
    public function setInGallery(bool $inGallery): void
    {
        $this->inGallery = $inGallery;
    }
}
