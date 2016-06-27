<?php

namespace AppBundle\Entity;

class PhotoFactory
{
    public function create()
    {
        $photo = new Photo();
        
        $photo->setAddedAt(new \DateTime());

        return $photo;
    }
}