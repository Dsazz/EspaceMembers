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

use Behat\Behat\Context\Context;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;

use Faker\Factory as FakerFactory;
use Faker\Generator;

use Application\Sonata\MediaBundle\Entity\Media;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * DefaultContext
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
abstract class DefaultContext extends RawMinkContext implements Context, KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Faker.
     *
     * @var Generator
     */
    protected $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function getService($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Generate page url.
     * This method uses simple convention where page argument is prefixed
     * with "sylius_" and used as route name passed to router generate method.
     *
     * @param object|string $page
     * @param array         $parameters
     *
     * @return string
     */
    protected function generatePageUrl($page, array $parameters = array())
    {
        if (is_object($page)) {
            return $this->generateUrl($page, $parameters);
        }

        $route  = str_replace(' ', '_', trim($page));
        $routes = $this->getContainer()->get('router')->getRouteCollection();

        if (null === $routes->get($route)) {
            $route = 'sylius_'.$route;
        }

        if (null === $routes->get($route)) {
            $route = str_replace('sylius_', 'sylius_backend_', $route);
        }

        $route = str_replace(array_keys($this->actions), array_values($this->actions), $route);
        $route = str_replace(' ', '_', $route);

        return $this->generateUrl($route, $parameters);
    }

    /**
     * Get current user instance.
     *
     * @return null|UserInterface
     *
     * @throws \Exception
     */
    protected function getUser()
    {
        $token = $this->getSecurityContext()->getToken();
        if (null === $token) {
            throw new \Exception('No token found in security context.');
        }

        return $token->getUser();
    }

    /**
     * Get security context.
     *
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->getService('security.context');
    }


    /**
     * Get Doctrine Entity Manager
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
         return $this->getService('doctrine.orm.entity_manager');
    }

    /**
     * Get repository of Group
     *
     * @return Doctrine\ORM\EntityRepository
     */
    public function getGroupRepository()
    {
        return $this->getEntityManager()->getRepository('ApplicationSonataUserBundle:Group');
    }

    /**
     * Get repository of Teaching
     *
     * @return Doctrine\ORM\EntityRepository
     */
    public function getTeachingRepository()
    {
        return $this->getEntityManager()->getRepository('EspaceMembersMainBundle:Teaching');
    }

    /**
     * Get repository of Direction
     *
     * @return Doctrine\ORM\EntityRepository
     */
    public function getDirectionRepository()
    {
        return $this->getEntityManager()->getRepository('EspaceMembersMainBundle:Direction');
    }

    /**
     * Get repository of Tag
     *
     * @return Doctrine\ORM\EntityRepository
     */
    public function getTagRepository()
    {
        return $this->getEntityManager()->getRepository('EspaceMembersMainBundle:Tag');
    }

    /**
     * Get repository of Event
     *
     * @return Doctrine\ORM\EntityRepository
     */
    public function getEventRepository()
    {
        return $this->getEntityManager()->getRepository('EspaceMembersMainBundle:Event');
    }

    /**
     * Get repository of Category
     *
     * @return Doctrine\ORM\EntityRepository
     */
    public function getCategoryRepository()
    {
        return $this->getEntityManager()->getRepository('EspaceMembersMainBundle:Category');
    }

    /**
     * Generate url.
     *
     * @param string  $route
     * @param array   $parameters
     * @param Boolean $absolute
     *
     * @return string
     */
    protected function generateUrl($route, array $parameters = array(), $absolute = false)
    {
        return $this->locatePath($this->getService('router')->generate($route, $parameters, $absolute));
    }

    /**
     * Opens specified page
     */
    public function visit($page)
    {
        $this->visitPath($page);
    }

    /**
     * Presses button with specified id|name|title|alt|value.
     */
    protected function pressButton($button)
    {
        $this->getSession()->getPage()->pressButton($this->fixStepArgument($button));
    }

    /**
     * Clicks link with specified id|title|alt|text.
     */
    protected function clickLink($link)
    {
        $this->getSession()->getPage()->clickLink($this->fixStepArgument($link));
    }

    /**
     * Fills in form field with specified id|name|label|value.
     */
    protected function fillField($field, $value)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);
        $this->getSession()->getPage()->fillField($field, $value);
    }

    /**
     * Selects option in select field with specified id|name|label|value.
     */
    public function selectOption($select, $option)
    {
        $this->getSession()->getPage()->selectFieldOption($this->fixStepArgument($select), $this->fixStepArgument($option));
    }

    /**
     * Returns fixed step argument (with \\" replaced back to ").
     *
     * @param string $argument
     *
     * @return string
     */
    protected function fixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }

    /**
     * Create new image
     *
     * @param string $context - context for SonataMediaBundle
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function createImageWithContext($context)
    {
        $mediaManager = $this->getService('sonata.media.manager.media');
        $media = new Media();

        $media->setProviderName('sonata.media.provider.image');
        $media->setContext($context);
        $media->setBinaryContent(
            $this->faker->image($dir = '/tmp', $width = 640, $height = 480)
        );

        $mediaManager->save($media);

        return $media;
    }

    /**
     * Create new mp3 lesson
     *
     * @return EspaceMembers\MainBundle\Entity\Media
     */
    public function createLessonMp3()
    {
        $mediaManager = $this->getService('sonata.media.manager.media');
        $media = new Media();

        $media->setProviderName('sonata.media.provider.file');
        $media->setContext('lesson');

        $fixturesPath = $this->getService('kernel')->getRootDir().'/../web/uploads/fixtures/';
        $media->setBinaryContent(
            $this->faker->file(
                sprintf('%s%s', $fixturesPath, 'mp3'),
                sprintf('%s%s', $fixturesPath, 'mp3_test')
            )
        );

        $mediaManager->save($media);

        return $media;
    }

    /**
     * Returns current active mink session.
     *
     * @return \Symfony\Component\BrowserKit\Client
     *
     * @throws \Behat\Mink\Exception\UnsupportedDriverActionException
     */
    protected function getClient()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof BrowserKitDriver) {
            $message = 'This step is only supported by the browserkit drivers';
            throw new UnsupportedDriverActionException($message, $driver);
        }
        return $driver->getClient();
    }

}
