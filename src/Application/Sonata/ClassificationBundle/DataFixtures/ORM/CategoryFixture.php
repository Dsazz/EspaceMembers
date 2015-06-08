<?php

namespace Application\Sonata\ClassificationBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\Entity\Media;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\ClassificationBundle\Entity\Category;

/**
 * Adds media context to database
 */
class CategoryFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $categories = ['default', 'avatar', 'lesson', 'cover', 'flayer'];

        foreach ($categories as $category) {
            $manager->persist($this->createCategory($category));
        }

        $manager->flush();
    }

    private function createCategory($categoryName)
    {
        $category = new Category();

        $category->setName($categoryName);
        $category->setDescription($categoryName);
        $category->setContext($this->getReference('context_' . $categoryName));
        $category->setEnabled(true);

        return $category;
    }

    public function getOrder()
    {
        return 2;
    }
}
