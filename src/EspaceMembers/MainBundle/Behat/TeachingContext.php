<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use EspaceMembers\MainBundle\Entity\Category;
use EspaceMembers\MainBundle\Entity\Event;
use EspaceMembers\MainBundle\Entity\Tag;
use EspaceMembers\MainBundle\Entity\Teaching;
use EspaceMembers\MainBundle\Entity\Direction;

use Application\Sonata\UserBundle\Entity\User;
use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\MediaBundle\Entity\Media;

/**
 * TeachingContext
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
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

            isset($data['first name']) && !empty($data['first name'])
                ? $teacher->setFirstname(trim($data['first name']))
                : $teacher->setFirstname($this->faker->firstNameMale);

            isset($data['last name']) && !empty($data['last name'])
                ? $teacher->setLastname(trim($data['last name']))
                : $teacher->setLastname($this->faker->lastName);

            isset($data['biography']) && !empty($data['biography'])
                ? $teacher->setBiography($data['biography'])
                : $teacher->setBiography($this->faker->paragraph(5));

            isset($data['gender']) && !empty($data['gender'])
                ? $teacher->setGender($data['gender'])
                : $teacher->setGender('m');

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

            $teacher->setDateOfBirth($this->faker->dateTimeBetween('-30 years', '-20 year'));

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
            $category = new Category();

            isset($data['name']) && !empty($data['name'])
                ? $category->setName(trim($data['name']))
                : $category->setName($this->faker->unique()->word);

            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * @Given /^there are directions:$/
     * @Given /^there are following directions:$/
     * @Given /^the following directions exist:$/
     */
    public function thereAreDirections(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            $direction = new Direction();

            isset($data['name']) && !empty($data['name'])
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
            $tag = new Tag();

            isset($data['name']) && !empty($data['name'])
                ? $tag->setName(trim($data['name']))
                : $tag->setName($this->faker->unique()->word);

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
                        ->findOneBy(array('name' => trim($tag)));

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
                    ->findOneBy(array('name' => trim($data['category'])));

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
                    $direction = $this->getDirectionRepository()
                        ->findOneBy(array('name' => trim($direction)));

                    $teaching->addDirection($direction);
                }
            }

            if (isset($data['tags']) && !empty($data['tags'])) {
                foreach (explode(',', $data['tags']) as $tag) {
                    $tag = $this->getTagRepository()
                        ->findOneBy(array('name' => trim($tag)));

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
