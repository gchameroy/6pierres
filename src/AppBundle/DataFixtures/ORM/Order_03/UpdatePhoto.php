<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UpdatePhoto extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 3;
    }

    public function load(ObjectManager $manager)
    {
        $projects = array(
            1 => array(1, 2),
            2 => array(1, 2),
            3 => array(1, 2, 3),
            4 => array(1, 2, 3, 4),
            5 => array(1, 2),
            6 => array(1, 2, 3, 4, 5),
            7 => array(1, 2, 3, 4, 5),
            8 => array(1, 2, 3),
            9 => array(1)
        );
        
        $i = 1;
        foreach($projects As $id_project => $photos){
            foreach($photos As $path){
                $path = $path == 'cover' ? $path : (int) $path;
                $photo = $this->getReference('photo-' . $id_project . '-' . $path)
                    ->setProject($this->getReference('project-' . $id_project));
                $manager->persist($photo);
                $manager->flush();
                $i++;
            }
        }
    }
}