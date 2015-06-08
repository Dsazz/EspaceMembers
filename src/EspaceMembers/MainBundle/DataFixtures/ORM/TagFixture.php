<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use EspaceMembers\MainBundle\Entity\Tag;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends AbstractFixture implements OrderedFixtureInterface
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
            'Программирование ПО',
            'Филология',
            'Искусство',
            'Биотехнология',
            'Электротехника',
            'Психология',
            'Риторика'
        ];

        foreach ($directions as $i => $direction) {
            $direction = $this->createTag(
                sprintf('tag%d', $i), $direction
            );
            $manager->persist($direction);
        }

        $manager->flush();
    }

    private function createTag($reference, $directionName)
    {
        $direction = new Tag();
        $direction->setName($directionName);

        $this->setReference($reference, $direction);

        return $direction;
    }

    public function getOrder()
    {
        return 6;
    }
}
