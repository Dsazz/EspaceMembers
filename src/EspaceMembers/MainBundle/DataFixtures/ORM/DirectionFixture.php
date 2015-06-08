<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use EspaceMembers\MainBundle\Entity\Direction;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DirectionFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $directions = [
            'История',
            'Философия',
            'Экономика',
            'Программирование',
            'Филология',
            'Искусство',
            'Биотехнология',
            'Электротехника',
            'Психология',
            'Риторика'
        ];

        foreach ($directions as $i => $direction) {
            $direction = $this->createDirection(
                sprintf('direction%d', $i), $direction
            );
            $manager->persist($direction);
        }

        $manager->flush();
    }

    private function createDirection($reference, $directionName)
    {
        $direction = new Direction();
        $direction->setName($directionName);

        $this->setReference($reference, $direction);

        return $direction;
    }

    public function getOrder()
    {
        return 5;
    }
}
