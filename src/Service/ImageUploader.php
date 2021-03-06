<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    public function __construct(ContainerInterface $container)
    {
        $this->targetDir = $container->getParameter('images_directory');
    }

    /**
     * @var string
     */
    private $targetDir;

    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid().'.'.$file->guessExtension());

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    /**
     * @return string
     */
    public function getTargetDir(): string
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
