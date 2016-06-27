<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProject extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
		for($i=1; $i<=9; $i++){
			$project = $this->container->get('app.project.factory')->create()
				->setName('Projet N° ' . $i)
				->setCompletedAt(new \DateTime())
				->setOrder(10 - $i)
				->setPhoto($this->getReference('photo-' . $i . '-cover'));
			$manager->persist($project);
			$this->addReference('project-' . $i, $project);
		}

		$manager->flush();
    }
}