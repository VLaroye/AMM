<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
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
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $alt;

    /**
     * @var UploadedFile
     *
     * @Assert\Image(
     *     groups={"artistImage"},
     *     minWidth=500,
     *     minWidthMessage="La largeur de l'image est trop faible : {{ width }}px. Largeur minimum : {{ min_width }}",
     *     minHeight=450,
     *     minHeightMessage="La hauteur de l'image est trop faible : {{ height }}px. Hauteur minimum : {{ min_height }}",
     *     minRatio=0.8,
     *     minRatioMessage="Le ratio (largeur/hauteur) est trop faible : {{ ratio }}. Ratio minimum : {{ min_ratio }}",
     *     maxRatio=1.2,
     *     maxRatioMessage="Le ratio (largeur/hauteur) est trop grand : {{ ratio }}. Ratio maximal : {{ max_ratio }}"
     * )
     *
     * @Assert\Image(
     *     minWidth=400,
     *     minWidthMessage="La largeur de l'image est trop faible : {{ width }}px. Largeur minimum : {{ min_width }}",
     *     minRatio=0.8,
     *     minRatioMessage="Le ratio (largeur/hauteur) est trop faible : {{ ratio }}. Ratio minimum : {{ min_ratio }}",
     *     maxRatio=1.2,
     *     maxRatioMessage="Le ratio (largeur/hauteur) est trop grand : {{ ratio }}. Ratio maximal : {{ max_ratio }}",
     *     groups={"eventImage"}
     * )
     */
    private $file;

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
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getAlt(): ?string
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
     * @return UploadedFile
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }
}
