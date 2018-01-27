<?php

namespace App\Service;

class Slugify
{
    private $slugify;

    public function __construct(\Cocur\Slugify\Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function slugify($expression)
    {
        return $this->slugify->slugify($expression);
    }
}