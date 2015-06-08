<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use EspaceMembers\MainBundle\Entity\Category;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $categories = [
            'История востока',
            'Древнегреческая философия',
            'Государственная экономика',
            'Тестирование ПО',
            'Английский язык',
            'Мировая художественная культура',
            'Биомедицина',
            'Электромеханика',
            'Дифференциальная психология',
            'Дебаты'
        ];

        foreach ($categories as $i => $category) {
            $category = $this->createCategory(
                sprintf('category%d', $i), $category
            );
            $manager->persist($category);
        }

        $manager->flush();
    }

    private function createCategory($reference, $categoryName)
    {
        $category = new Category();
        $category->setName($categoryName);

        $this->setReference($reference, $category);

        return $category;
    }

    public function getOrder()
    {
        return 4;
    }
}
