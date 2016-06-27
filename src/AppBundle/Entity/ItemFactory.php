<?php

namespace AppBundle\Entity;

class ItemFactory
{
    public function create()
    {
        $item = new Item();

        return $item;
    }
}