<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPhoto extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        return 1;
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
        
        foreach($projects As $id_project => $photos){
            foreach($photos As $path){
                $size = $path == 'cover' ? 20000 : 100000;
                $photo = $this->container->get('app.photo.factory')->create()
                    ->setPath($id_project.'-'.$path.'.jpg')
                    ->setSize($size);
                $manager->persist($photo);
                $path = $path == 'cover' ? $path : (int) $path;
                $this->addReference('photo-'.(int) $id_project.'-'.$path, $photo);
            }
            $manager->flush();
        }
    }
}