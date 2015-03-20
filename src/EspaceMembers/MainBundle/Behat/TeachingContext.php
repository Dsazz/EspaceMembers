<?php

namespace EspaceMembers\MainBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use EspaceMembers\MainBundle\Entity\Category;
use EspaceMembers\MainBundle\Entity\Event;
use EspaceMembers\MainBundle\Entity\Group;
use EspaceMembers\MainBundle\Entity\Media;
use EspaceMembers\MainBundle\Entity\Tag;
use EspaceMembers\MainBundle\Entity\Teaching;
use EspaceMembers\MainBundle\Entity\User;
use EspaceMembers\MainBundle\Entity\Voie;

class TeachingContext extends DefaultContext
{
    /**
     * @Given /^there are users:$/
     * @Given /^there are following users:$/
     * @Given /^the following users exist:$/
     */
    public function thereAreUsers(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $teacher = new User();

            isset($data['email']) && !empty($data['email'])
                ? $teacher->setEmail($data['email'])
                : $teacher->setEmail($this->faker->unique()->freeEmail);

            isset($data['password']) && !empty($data['password'])
                ? $teacher->setPlainPassword($data['password'])
                : $teacher->setPlainPassword('test_password');

            isset($data['phone']) && !empty($data['phone'])
                ? $teacher->setPhone($data['phone'])
                : $teacher->setPhone($this->faker->phoneNumber);

            isset($data['address']) && !empty($data['address'])
                ? $teacher->setAddress($data['address'])
                : $teacher->setAddress($this->faker->address);

            isset($data['enabled']) && !empty($data['enabled'])
                ? $teacher->setEnabled($data['enabled'])
                : $teacher->setEnabled(true);

            //TODO: remove First Name -> Firstname already exists
            isset($data['first name']) && !empty($data['first name'])
                ? $teacher->setFirstName(trim($data['first name']))
                : $teacher->setFirstName($this->faker->firstNameMale);

            //TODO: remove Last Name -> Lastname already exists
            isset($data['last name']) && !empty($data['last name'])
                ? $teacher->setLastName(trim($data['last name']))
                : $teacher->setLastName($this->faker->lastName);

            //TODO: remove Description -> Biografy already exists
            isset($data['description']) && !empty($data['description'])
                ? $teacher->setDescription($data['description'])
                : $teacher->setDescription($this->faker->paragraph(5));

            //TODO: remove Sex -> Gender already exists
            isset($data['gender']) && !empty($data['gender'])
                ? $teacher->setSex($data['gender'])
                : $teacher->setSex('MALE');

            isset($data['is teacher']) && !empty($data['is teacher'])
                ? $teacher->setIsTeacher((bool)$data['is teacher'])
                : $teacher->setIsTeacher(false);

            if (isset($data['groups']) && !empty($data['groups'])) {
                foreach (explode(',', $data['groups']) as $group) {
                    $group = $this->getGroupRepository()
                        ->findOneBy(array('name' => trim($group)));

                    $teacher->addGroup($group);
                }
            }

            if (isset($data['roles']) && !empty($data['roles'])) {
                foreach (explode(',', $data['roles']) as $role) {
                    $teacher->addRole(trim($role));
                }
            }

            //TODO: remove Birthday -> Date of Birth already exists
            $teacher->setBirthday($this->faker->dateTimeBetween('-30 years', '-20 year'));

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

            isset($data['name']) && !empty($data['name'])
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
            //TODO: refactor title -> name
            $category = new Category();

            isset($data['name']) && !empty($data['name'])
                ? $category->setTitle(trim($data['name']))
                : $category->setTitle($this->faker->unique()->word);

            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are directions:$/
     * @Given /^there are following directions:$/
     * @Given /^the following directions exist:$/
     */
    public function thereAreVoies(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            //TODO: refactor title -> name
            $direction = new Voie();

            isset($data['name']) && !empty($data['name'])
                ? $direction->setTitle(trim($data['name']))
                : $direction->setTitle($this->faker->unique()->word);

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

            isset($data['name']) && !empty($data['name'])
                ? $tag->setTitle(trim($data['name']))
                : $tag->setTitle($this->faker->unique()->word);

            $manager->persist($tag);
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

            isset($data['title']) && !empty($data['title'])
                ? $event->setTitle(trim($data['title']))
                : $event->setTitle($this->faker->sentence(10));

            isset($data['year']) && !empty($data['year'])
                ? $event->setYear(trim($data['year']))
                : $event->setYear($this->faker->year('+5 years'));

            isset($data['description']) && !empty($data['description'])
                ? $event->setDescription(trim($data['resume']))
                : $event->setDescription($this->faker->text(200));

            isset($data['is show']) && !empty($data['is show'])
                ? $event->setIsShow((bool)trim($data['is show']))
                : $event->setIsShow(true);

            if (isset($data['tags']) && !empty($data['tags'])) {
                foreach (explode(',', $data['tags']) as $tag) {
                    $tag = $this->getTagRepository()
                        ->findOneBy(array('title' => trim($tag)));

                    $event->addTag($tag);
                }
            }

            if (isset($data['usernames']) && !empty($data['usernames'])) {
                foreach (explode(',', $data['usernames']) as $username) {
                    $user = $this->getService('fos_user.user_manager')
                        ->findUserByUsername(trim($username));

                    $event->addUser($user);
                }
            }

            if (isset($data['groups']) && !empty($data['groups'])) {
                foreach (explode(',', $data['groups']) as $group) {
                    $group = $this->getGroupRepository()
                        ->findOneBy(array('name' => trim($group)));

                    $event->addGroup($group);
                }
            }

            if (isset($data['category']) && !empty($data['category'])) {
                $category = $this->getCategoryRepository()
                    ->findOneBy(array('title' => trim($data['category'])));

                $event->setCategory($category);
            }

            $event->setStartDate($this->faker->dateTime('now'));
            $event->setCompletionDate($this->faker->dateTime('+10 days'));
            $event->setFrontImage($this->createImageWithContext('cover'));

            $manager->persist($event);
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

            isset($data['title']) && !empty($data['title'])
                ? $teaching->setTitle(trim($data['title']))
                : $teaching->setTitle($this->faker->sentence(10));

            isset($data['number days']) && !empty($data['number days'])
                ? $teaching->setDayNumber(trim($data['number days']))
                : $teaching->setDayNumber($this->faker->randomDigitNotNull);

            isset($data['resume']) && !empty($data['resume'])
                ? $teaching->setResume(trim($data['resume']))
                : $teaching->setResume($this->faker->text(200));

            isset($data['technical comment']) && !empty($data['technical comment'])
                ? $teaching->setTechnicalComment(trim($data['technical comment']))
                : $teaching->setTechnicalComment($this->faker->text(200));

            isset($data['is show']) && !empty($data['is show'])
                ? $teaching->setIsShow((bool)trim($data['is show']))
                : $teaching->setIsShow(true);

            isset($data['serial']) && !empty($data['serial'])
                ? $teaching->setSerial(trim($data['serial']))
                : $teaching->setSerial($this->faker->randomDigitNotNull);

            if (isset($data['directions']) && !empty($data['directions'])) {
                foreach (explode(',', $data['directions']) as $direction) {
                    $direction = $this->getVoieRepository()
                        ->findOneBy(array('title' => trim($direction)));

                    $teaching->addVoie($direction);
                }
            }

            if (isset($data['tags']) && !empty($data['tags'])) {
                foreach (explode(',', $data['tags']) as $tag) {
                    $tag = $this->getTagRepository()
                        ->findOneBy(array('title' => trim($tag)));

                    $teaching->addTag($tag);
                }
            }

            if (isset($data['usernames']) && !empty($data['usernames'])) {
                foreach (explode(',', $data['usernames']) as $username) {
                    $user = $this->getService('fos_user.user_manager')
                        ->findUserByUsername(trim($username));

                    $teaching->addUser($user);
                }
            }

            if (isset($data['event']) && !empty($data['event'])) {
                $event = $this->getEventRepository()
                    ->findOneBy(array('title' => trim($data['event'])));

                $teaching->setEvent($event);
            }

            $teaching->setDate($this->faker->dateTime('now'));
            $teaching->setDayTime(
                array_rand(array('matin', 'midi', 'soir', 'minuit'))
            );
            $teaching->setLesson($this->createLessonMp3());

            $manager->persist($teaching);
        }

        $manager->flush();
    }
}
