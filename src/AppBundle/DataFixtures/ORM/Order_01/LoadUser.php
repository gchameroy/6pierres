<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $user_admin_1 = $this->container->get('app.user.factory')->createAdmin()
            ->setUsername('admin1')
            ->setEmail('admin1@geoffrey-chameroy.fr')
            ->setPlainPassword('Pwd@Admin-1')
            ->setSalt(md5(random_bytes(15)));
        $password = $this->container
            ->get('security.password_encoder')
            ->encodePassword($user_admin_1, $user_admin_1->getPlainPassword());
        $user_admin_1->setPassword($password)
            ->eraseCredentials();
        $manager->persist($user_admin_1);
        $manager->flush();

		$user_1 = $this->container->get('app.user.factory')->createUser()
            ->setUsername('user1')
            ->setEmail('user1@geoffrey-chameroy.fr')
            ->setPlainPassword('Pwd@User-1')
            ->setSalt(md5(random_bytes(15)));
        $password = $this->container
            ->get('security.password_encoder')
            ->encodePassword($user_1, $user_1->getPlainPassword());
        $user_1->setPassword($password)
            ->eraseCredentials();
        $manager->persist($user_1);
        $manager->flush();
        
        $this->addReference('user-admin-1', $user_admin_1);
        $this->addReference('user-1', $user_1);
    }
}