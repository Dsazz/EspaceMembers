<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\Entity\User;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Faker\Factory as FakerFactory;

/**
 * Adds test users to database
 */
class UserFixture extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $teacher = $this->createUser(sprintf('teacher%d', $i), $i, 1);
            $teacher->addRole('ROLE_ADMIN');
            $teacher->addGroup($this->getReference('group0'));

            $manager->persist($teacher);
        }

        $superadmin = $this->createSuperAdmin();
        $manager->persist($superadmin);

        $student = $this->createStudent();
        $manager->persist($student);

        $manager->flush();
    }

    public function getOrder()
    {
        return 8;
    }

    private function createSuperAdmin()
    {
        $username = 'superadmin';
        $user = $this->createUser($username, $username);

        $user->addRole('ROLE_SUPER_ADMIN');

        return $user;
    }

    private function createStudent()
    {
        $username = 'student';
        $user = $this->createUser($username, $username);
        $user->addRole('ROLE_USER');
        $user->addGroup(
            $this->getReference('group1')
        );

        return $user;
    }

    private function createUser($username, $id, $isTeacher = 0)
    {
        $user = new User();
        $faker = FakerFactory::create('ru_RU');

        $user->setAvatar($this->getReference('user_avatar' . $id));
        $user->setUsername($username);
        $user->setFirstname($faker->firstName('male'));
        $user->setLastname($faker->lastName);
        $user->setEmail($username . '@test.com');
        $user->setPlainPassword('test');
        $user->setDateOfBirth($faker->dateTimeBetween('-30 years', '-20 year'));
        $user->setPhone($faker->phoneNumber());
        $user->setAddress($faker->address());
        $user->setBiography($faker->realText(255));
        $user->setGender('m');
        $user->setIsTeacher($isTeacher);
        $user->setEnabled(true);

        $this->addReference($username, $user);

        return $user;
    }
}
