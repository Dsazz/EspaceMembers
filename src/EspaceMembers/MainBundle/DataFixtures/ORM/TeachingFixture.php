<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use EspaceMembers\MainBundle\Entity\Teaching;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Faker\Factory as FakerFactory;

/**
 * Adds test users to database
 */
class TeachingFixture extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $lectionsMp3 = [
            '"История востока 19 века"',
            '"Античная философия"',
            '"Предприятие в условиях рыночной экономики"',
            '"Виды тестирования программного обеспечения"',
            '"Существительное и оборот there + to be"',
            '"Современная украинская художественная литература"',
            '"Основы биомедицинской этики"',
            '"Основы электромеханики"',
            '"Предмет и задачи дифференциальной психологии"',
            '"Ораторское искусство и риторика"'
        ];

        $lectionsVideo = [
            '"История востока 18 века"',
            '"Античная философия"',
            '"Предприятие в условиях рыночной экономики"',
            '"Виды тестирования программного обеспечения"',
            '"Существительное и оборот there + to be"',
            '"Современная американская художественная литература"',
            '"Основы биомедицинской этики"',
            '"Основы электромеханики"',
            '"Предмет и задачи дифференциальной психологии"',
            '"Ораторское искусство и риторика"'
        ];

        foreach ($lectionsMp3 as $i => $lectionMp3) {
            $lectionMp3 = $this->createTeaching(
                sprintf('lectionMp%d', $i), $i, $lectionMp3
            );

            $lectionMp3->setSerial(1);
            $lectionMp3->setLesson(
                $this->getReference('lessonMP3' . $i)
            );

            $manager->persist($lectionMp3);
        }

        foreach ($lectionsVideo as $i => $lectionVideo) {
            $lectionVideo = $this->createTeaching(
                sprintf('lectionVideo%d', $i), $i, $lectionVideo
            );

            $lectionVideo->setSerial(2);
            $lectionVideo->setLesson(
                $this->getReference('lessonVideo' . $i)
            );

            $manager->persist($lectionVideo);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }

    private function createTeaching($reference, $id, $title)
    {
        $teaching = new Teaching();
        $faker = FakerFactory::create('ru_RU');

        $teaching->setTitle($title);
        $teaching->setDate($faker->dateTime('now'));
        $teaching->setDayNumber($faker->numberBetween(1, 30));
        $teaching->setDayTime($this->getRandTimeDay());
        $teaching->setResume($faker->realText(255));
        $teaching->setTechnicalComment($faker->realText(255));
        $teaching->setIsShow(true);
        $teaching->setEvent($this->getReference('event' . $id));
        $teaching->addDirection($this->getReference('direction' . $id));
        $teaching->addTag($this->getReference('tag' . $id));
        $teaching->addUser($this->getReference('teacher' . $id));

        $this->addReference($reference, $teaching);

        return $teaching;
    }

    public function getRandTimeDay()
    {
        $time = array(
            'утро', 'день', 'вечер'
        );

        return $time[array_rand($time)];
    }
}
