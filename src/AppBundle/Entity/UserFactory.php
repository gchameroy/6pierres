<?php

namespace AppBundle\Entity;

class UserFactory
{
    public function createUser()
    {
        $user = new User();

        $user->setIsAdmin(false);
        $user->setRegisteredAt(new \DateTime());

        return $user;
    }

    public function createAdmin()
    {
        $user = new User();

        $user->setIsAdmin(true);
        $user->setRegisteredAt(new \DateTime());

        return $user;
    }
}