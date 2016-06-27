<?php

namespace AppBundle\Entity;

class PhotoFactory
{
    public function create()
    {
        $photo = new Photo();

        return $photo;
    }
}