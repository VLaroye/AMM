<?php

namespace App\DoctrineListener;

use App\Entity\Image;
use App\Entity\SliderImage;
use App\Service\ImageFileDeleter;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class ImageDeleteListener
{
    private $deleter;

    public function __construct(ImageFileDeleter $deleter)
    {
        $this->deleter = $deleter;
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Image && !$entity instanceof SliderImage) {
            return;
        }

        $this->deleter->deleteImageFile($entity->getFileName());
    }
}