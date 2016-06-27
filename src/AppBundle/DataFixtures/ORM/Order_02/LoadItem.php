<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadItem extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $projects = array(
            '01' => array('cover','01','02'),
            '02' => array('cover','01','02','03'),
            '03' => array('cover','01','02','03'),
            '04' => array('cover','01','02','03','04'),
            '05' => array('cover','01','02'),
            '06' => array('cover','01','02','03','04','05'),
            '07' => array('cover','01','02','03','04','05'),
            '08' => array('cover','01','02','03'),
            '09' => array('cover','01')
        );
        
        $i = 1;
        foreach($projects As $id_project => $photos){
            foreach($photos As $path){
                $path = $path == 'cover' ? $path : (int) $path;
                $item = $this->container->get('app.item.factory')->create()
                    ->setPhoto($this->getReference('photo-'.(int) $id_project.'-'.$path))
                    ->setProject($this->getReference('project-'.(int) $id_project));
                $manager->persist($item);
                $manager->flush();
                $this->addReference('item-'.(int) $i, $item);
                $i++;
            }
        }
    }
}