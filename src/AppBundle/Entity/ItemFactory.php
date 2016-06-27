<?php

namespace AppBundle\Entity;

class ItemFactory
{
    public function create()
    {
        $item = new Item();
        
        $item->setAddedAt(new \DateTime());

        return $item;
    }
}