<?php

namespace EspaceMembers\MainBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\Entity\Media;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Faker\Factory as FakerFactory;

/**
 * Adds medias to database
 */
class MediaFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = NULL)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $userAvatar = $this->createImage(sprintf('user_avatar%d', $i), 'avatar');
            $manager->persist($userAvatar);
        }

        $studentAvatar = $this->createImage('user_avatarstudent', 'avatar');
        $manager->persist($studentAvatar);

        $adminAvatar = $this->createImage('user_avatarsuperadmin', 'avatar');
        $manager->persist($adminAvatar);

        for ($i = 0; $i < 30; $i++) {
            $cover = $this->createImage(sprintf("cover%d", $i), 'cover');
            $manager->persist($cover);
        }

        for ($i = 0; $i < 10; $i++) {
            $lessonMp3 = $this->createMedia(sprintf('lessonMP3%d', $i), 'mp3');
            $manager->persist($lessonMp3);
        }

        for ($i = 0; $i < 10; $i++) {
            $lessonVideo = $this->createMedia(sprintf('lessonVideo%d', $i), 'video');
            $manager->persist($lessonVideo);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

    /**
     * Create image for category.
     *
     * @return Media
     */
    private function createImage(
        $reference,
        $context,
        $name = 'people',
        $providerName = 'sonata.media.provider.image',
        $width = 640,
        $height = 480
    ) {
        $faker = FakerFactory::create();
        $categoryManager = $this->container->get('sonata.classification.manager.category');

        $image = new Media;
        $image->setProviderName($providerName);
        $image->setContext($context);
        $image->setBinaryContent($faker->image($dir = '/tmp', $width, $height, $name));
        $image->setCategory($categoryManager->getCategories()[0]);

        $this->setReference($reference, $image);

        return $image;
    }

    private function createMedia(
        $reference,
        $type = 'video'
    ) {
        $faker = FakerFactory::create();
        $categoryManager = $this->container->get('sonata.classification.manager.category');

        $media = new Media;
        $media->setProviderName('sonata.media.provider.file');
        $media->setContext('lesson');
        $media->setBinaryContent(
            $this->generateBinaryContext($type)
        );
        $media->setCategory($categoryManager->getCategories()[0]);

        $this->setReference($reference, $media);

        return $media;
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
}
