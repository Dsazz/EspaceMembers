<?php

namespace EspaceMembers\MainBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;


class TeachingContext extends DefaultContext
{
    /**
     * @Given /^there are teachers:$/
     * @Given /^there are following teachers:$/
     * @Given /^the following teachers exist:$/
     */
    public function thereAreTeachers(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $teacher = new User();

            isset($data['email'])
                ? $teacher->setEmail($data['email'])
                : $teacher->setEmail($this->faker->unique()->freeEmail);

            isset($data['password'])
                ? $teacher->setPlainPassword($data['password'])
                : $teacher->setPlainPassword('teacher');

            isset($data['phone'])
                ? $teacher->setPhone($data['phone'])
                : $teacher->setPhone($this->faker->phoneNumber);

            isset($data['address'])
                ? $teacher->setAddress($data['address'])
                : $teacher->setAddress($this->faker->address);

            isset($data['enabled'])
                ? $teacher->setEnabled($data['enabled'])
                : $teacher->setEnabled(true);

            //TODO: remove First Name -> Firstname already exists
            isset($data['first name'])
                ? $teacher->setFirstName(trim($data['first name']))
                : $teacher->setFirstName($this->faker->firstNameMale);

            //TODO: remove Last Name -> Lastname already exists
            isset($data['last name'])
                ? $teacher->setLastName(trim($data['last name']))
                : $teacher->setLastName($this->faker->lastName);

            //TODO: remove Description -> Biografy already exists
            isset($data['description'])
                ? $teacher->setDescription($data['description'])
                : $teacher->setDescription($this->faker->paragraph(5));

            //TODO: remove Birthday -> Date of Birth already exists
            isset($data['birthday'])
                ? $teacher->setBirthday($data['birthday'])
                : $teacher->setBirthday($this->faker->dateTimeBetween('-30 years', '-20 year'));

            //TODO: remove Sex -> Gender already exists
            isset($data['sex'])
                ? $teacher->setSex($data['sex'])
                : $teacher->setSex('MALE');

            if (isset($data['group']) && !empty($data['group'])) {
                foreach (explode(',', $data['group']) as $group) {
                    $group = $this->getGroupRepository()
                        ->findOneBy(array('name' => trim($group)));

                    $teacher->addGroup($group);
                }
            }

            $teacher->setIsTeacher(true);
            $teacher->addRole(User::ROLE_ADMIN);
            $teacher->setAvatar($this->createImageWithContext('avatar'));

            $manager->persist($teacher);
        }

        $manager->flush();
    }


    /**
     * @Given /^there are groups:$/
     * @Given /^there are following groups:$/
     * @Given /^the following groups exist:$/
     */
    public function thereAreGroups(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $group = new Group();

            isset($data['name'])
                ? $group->setName(trim($data['name']))
                : $group->setName($this->faker->unique()->word);

            $manager->persist($group);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are categories:$/
     * @Given /^there are following categories:$/
     * @Given /^the following categories exist:$/
     */
    public function thereAreCategories(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $category = new Category();

            isset($data['name'])
                ? $category->setName(trim($data['name']))
                : $category->setName($this->faker->unique()->word);

            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are voies:$/
     * @Given /^there are following voies:$/
     * @Given /^the following voies exist:$/
     */
    public function thereAreVoies(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $direction = new Voie();

            isset($data['name'])
                ? $direction->setName(trim($data['name']))
                : $direction->setName($this->faker->unique()->word);

            $manager->persist($direction);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are tags:$/
     * @Given /^there are following tags:$/
     * @Given /^the following tags exist:$/
     */
    public function thereAreTags(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            //TODO: refactor Title -> Name in entity Tag
            $tag = new Tag();

            isset($data['name'])
                ? $tag->setTitle(trim($data['name']))
                : $tag->setTitle($this->faker->unique()->word);

            $manager->persist($tag);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are teachings:$/
     * @Given /^there are following teachings:$/
     * @Given /^the following teachings exist:$/
     */
    public function thereAreTeachings(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $teaching = new Teaching();

            isset($data['title'])
                ? $teaching->setTitle(trim($data['title']))
                : $teaching->setTitle($this->faker->sentence(10));

            isset($data['number days'])
                ? $teaching->setDayNumber(trim($data['number days']))
                : $teaching->setDayNumber($this->faker->randomDigitNotNull);

            isset($data['resume'])
                ? $teaching->setResume(trim($data['resume']))
                : $teaching->setResume($this->faker->text(200));

            isset($data['technical comment'])
                ? $teaching->setTechnicalComment(trim($data['technical comment']))
                : $teaching->setTechnicalComment($this->faker->text(200));

            isset($data['is show'])
                ? $teaching->setIsShow(trim($data['is show']))
                : $teaching->setIsShow(true);

            isset($data['serial'])
                ? $teaching->setSerial(trim($data['serial']))
                : $teaching->setSerial($this->faker->randomDigitNotNull);

            if (isset($data['direction']) && !empty($data['direction'])) {
                foreach (explode(',', $data['direction']) as $direction) {
                    $group = $this->getVoieRepository()
                        ->findOneBy(array('name' => trim($direction)));

                    $teacher->addVoie($direction);
                }
            }

            if (isset($data['tag']) && !empty($data['tag'])) {
                foreach (explode(',', $data['tag']) as $tag) {
                    $group = $this->getTagRepository()
                        ->findOneBy(array('title' => trim($tag)));

                    $teacher->addTag($tag);
                }
            }

            if (isset($data['username']) && !empty($data['username'])) {
                foreach (explode(',', $data['username']) as $username) {
                    $user = $this->getService('fos_user.user_manager')
                        ->findUserByUsername($username);

                    $event->addUser($user);
                }
            }

            if (isset($data['event']) && !empty($data['event'])) {
                $event = $this->getEventRepository()
                    ->findOneBy(array('title' => trim($data['event'])));

                $teacher->setEvent($event);
            }

            $teaching->setDate($this->faker->dayTime('now'));
            $teaching->setDateTime($this->faker->randTimeDay);
            $teaching->setLesson($this->createLessonMp3());

            $manager->persist($teaching);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are events:$/
     * @Given /^there are following events:$/
     * @Given /^the following events exist:$/
     */
    public function thereAreEvents(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $event = new Event();

            isset($data['title'])
                ? $event->setTitle(trim($data['title']))
                : $event->setTitle($this->faker->sentence(10));

            isset($data['year'])
                ? $event->setYear(trim($data['year']))
                : $event->setYear($this->faker->year('+5 years'));

            isset($data['description'])
                ? $event->setResume(trim($data['resume']))
                : $event->setResume($this->faker->text(200));

            isset($data['is show'])
                ? $event->setIsShow(trim($data['is show']))
                : $event->setIsShow(true);

            if (isset($data['tag']) && !empty($data['tag'])) {
                foreach (explode(',', $data['tag']) as $tag) {
                    $group = $this->getTagRepository()
                        ->findOneBy(array('title' => trim($tag)));

                    $event->addTag($tag);
                }
            }

            if (isset($data['username']) && !empty($data['username'])) {
                foreach (explode(',', $data['username']) as $username) {
                    $user = $this->getService('fos_user.user_manager')
                        ->findUserByUsername($username);

                    $event->addUser($user);
                }
            }

            if (isset($data['group']) && !empty($data['group'])) {
                foreach (explode(',', $data['group']) as $group) {
                    $group = $this->getGroupRepository()
                        ->findOneBy(array('name' => trim($group)));

                    $event->addGroup($group);
                }
            }

            if (isset($data['category']) && !empty($data['category'])) {
                $category = $this->getGroupRepository()
                    ->findOneBy(array('name' => trim($data['category'])));

                $teacher->setCategory($category);
            }

            $event->setStartDate($this->faker->dayTime('now'));
            $event->setCompletionDate($this->faker->dayTime('+10 days'));
            $event->setFrontImage($this->createImageWithContext('cover'));

            $manager->persist($teaching);
        }

        $manager->flush();
    }

}
