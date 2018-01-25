<?php

namespace App\Service;


use Symfony\Component\Filesystem\Filesystem;

class ImageFileDeleter
{
    private $fs;
    private $imagesDirectory;

    public function __construct(Filesystem $fs, $imagesDirectory)
    {
        $this->fs = $fs;
        $this->imagesDirectory = $imagesDirectory;
    }

    public function deleteImageFile(string $imageFileName): void
    {
        $this->fs->remove($this->imagesDirectory . '/' . $imageFileName);
    }
}