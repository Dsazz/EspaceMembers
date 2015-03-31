<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Nelmio\Alice\Fixtures;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * LoadFixtureData
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class LoadFixtureData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    protected $fixturesPath = "/../../../../../web/uploads/fixtures/";
    protected $uploadedPath = "/../../../../../web/uploads/media/";


    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $om)
    {
        Fixtures::load(__DIR__.'/EspaceMembers.yml', $om, array('providers' => array($this)));
    }

    private function removeOldMedia($path, $mediaType)
    {
        $fs = new Filesystem();

        $mediaType == 'mp3' || $mediaType == 'video' ? $mediaType = 'lesson' : '';
        $mediaFiles = $this->findFilesByMediaType($path, $mediaType);

        $fs->remove($mediaFiles);
    }

    private function findFilesByMediaType($path, $mediaType)
    {
        $finder = new Finder();
        $binaryContents = array();

        foreach ($finder->files()->in(sprintf('%s%s%s', __DIR__, $path, $mediaType)) as $key => $file) {
            $binaryContents[] = $file->getRealPath();
        }

        return $binaryContents;
    }

    /**
     * {@inheritdoc}
     */
    public function generateBinaryContext($mediaType)
    {
        $this->removeOldMedia($this->uploadedPath, $mediaType);
        $binaryContents = $this->findFilesByMediaType($this->fixturesPath, $mediaType);

        return $binaryContents[array_rand($binaryContents)];
    }

    public function randTimeDay()
    {
        $time = array(
            'matin', 'midi', 'soir', 'minuit'
        );

        return $time[array_rand($time)];
    }

    public function randGender()
    {
        $sex = array('m', 'f');

        return $gender[array_rand($gender)];
    }

    public function randRole()
    {
        $role = array(
            'ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'
        );

        return $role[array_rand($role)];
    }
}
