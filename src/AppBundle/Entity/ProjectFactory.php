<?php

namespace AppBundle\Entity;

class ProjectFactory
{
    public function create()
    {
        $project = new Project();

        $project->setAddedAt(new \DateTime());
        $project->setCompletedAt(new \DateTime());
        $project->setOrder(1);

        return $project;
    }
}