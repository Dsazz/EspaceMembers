<?php

namespace Application\Sonata\ClassificationBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\Entity\Media;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\ClassificationBundle\Entity\Context;

/**
 * Adds media context to database
 */
class ContextFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $contexts = ['default', 'avatar', 'lesson', 'cover', 'flayer'];

        foreach ($contexts as $context) {
            $manager->persist($this->createContext($context));
        }

        $manager->flush();
    }

    private function createContext($contextName)
    {
        $context = new Context();

        $context->setId($contextName);
        $context->setName($contextName);
        $context->setEnabled(true);

        $this->setReference("context_" . $contextName, $context);

        return $context;
    }

    public function getOrder()
    {
        return 1;
    }
}
