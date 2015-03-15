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

                    $teacher>addGroup($group);
                }
            }

            $teacher->setIsTeacher(true);
            $teacher->addRole(User::ROLE_ADMIN);
            $teacher->setAvatar($this->createImageWithContext('avatar'));

            $manager->persist($teacher);
            //teachings: [@teachingMP3_<current()>, @teachingVideo_<current()>]
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

            isset($data['number days'])
                ? $teaching->setDayNumber(trim($data['number days']))
                : $teaching->setDayNumber($this->faker->randomDigitNotNull);

            $teaching->setDate($this->faker->dayTime('now'));
            $teaching->setDateTime($this->faker->randTimeDay);

            $manager->persist($teaching);
        }

        $manager->flush();
    }
EspaceMembers\MainBundle\Entity\Teaching:
    teaching_prototype(template):
        title: <word()>
        date: <dateTime('now')>
        dayNumber: <randomDigitNotNull()>
        dayTime: <randTimeDay()>
        resume: <fr_FR:text(200)>
        technical_comment: <fr_FR:text(200)>
        is_show: <boolean(50)>
        voies(unique): [@voie*, @voie*]
        tags: [@tag*]
    teachingMP3_{1..10}(extends teaching_prototype):
        is_show: 1
        serial: 1
        lesson: @lessonMP3<current()>
    teachingVideo_{1..10}(extends teaching_prototype):
        serial: 2
        lesson: @lessonVideo<current()>
}
