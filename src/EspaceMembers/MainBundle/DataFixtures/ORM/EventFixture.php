<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use EspaceMembers\MainBundle\Entity\Event;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Faker\Factory as FakerFactory;

/**
 * Adds test users to database
 */
class EventFixture extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $events = [
            'Лекция по истории',
            'Лекция по философии',
            'Лекция по экономике',
            'Лекция по тестированию',
            'Лекция по английскому языку',
            'Доклад по мировой художественной культуре',
            'Доклад по биомедицине',
            'Доклад по электромеханике',
            'Лекция по дифференциальной психологии',
            'Лекция по риторике'
        ];

        foreach ($events as $i => $event) {
            $event = $this->createEvent(sprintf('event%d', $i), $i, $event);

            $manager->persist($event);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 9;
    }

    private function createEvent($reference, $id, $title)
    {
        $event = new Event();
        $faker = FakerFactory::create('ru_RU');

        $event->setTitle($title);
        $event->setStartDate($faker->dateTime('now'));
        $event->setCompletionDate($faker->dateTime('+10 days'));
        $event->setYear($faker->year());
        $event->setDescription($faker->realText(200));
        $event->setIsShow(true);
        $event->addTag($this->getReference('tag' . $id));
        $event->setFrontImage($this->getReference('cover' . $id));
        $event->setCategory($this->getReference('category' . $id));
        $event->addUser($this->getReference('teacher' . $id));

        $this->addReference($reference, $event);

        return $event;
    }
}
