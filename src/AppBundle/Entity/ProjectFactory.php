<?php

namespace AppBundle\Entity;

class ProjectFactory
{
    public function create()
    {
        $project = new Project();

        $project->setAddedAt(new \DateTime());

        return $project;
    }
}