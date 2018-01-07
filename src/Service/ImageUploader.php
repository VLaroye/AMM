<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->targetDir = $container->getParameter('images_directory');
    }

    /**
     * @var string
     */
    private $targetDir;

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid().'.'.$file->guessExtension());

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    /**
     * @return string
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }

    /**
     * @param string $targetDir
     */
    public function setTargetDir(string $targetDir): void
    {
        $this->targetDir = $targetDir;
    }
}
