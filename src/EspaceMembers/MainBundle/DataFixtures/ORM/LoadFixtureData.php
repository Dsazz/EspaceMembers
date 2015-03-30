<?php

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

    //For random sex of user
    public function randTimeDay()
    {
        $time = array(
            'matin', 'midi', 'soir', 'minuit'
        );

        return $time[array_rand($time)];
    }

    //For random sex of user
    public function randSex()
    {
        $sex = array('m', 'f');

        return $sex[array_rand($sex)];
    }

    //For random role of user
    public function randRole()
    {
        $role = array(
            'ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'
        );

        return $role[array_rand($role)];
    }
}
