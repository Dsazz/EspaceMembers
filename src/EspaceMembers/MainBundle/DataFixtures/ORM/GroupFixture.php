<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\Entity\Group;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GroupFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $directions = [
            'Преподаватели', 'Студенты'
        ];

        foreach ($directions as $i => $direction) {
            $direction = $this->createGroup(
                sprintf('group%d', $i), $direction
            );
            $manager->persist($direction);
        }

        $manager->flush();
    }

    private function createGroup($reference, $directionName)
    {
        $direction = new Group();
        $direction->setName($directionName);

        $this->setReference($reference, $direction);

        return $direction;
    }

    public function getOrder()
    {
        return 7;
    }
}
