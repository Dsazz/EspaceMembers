<?php

namespace EspaceMembers\MainBundle\Twig\Extension;

use Sonata\MediaBundle\Twig\TokenParser\MediaTokenParser;
use Sonata\MediaBundle\Twig\TokenParser\ThumbnailTokenParser;
use Sonata\MediaBundle\Twig\TokenParser\PathTokenParser;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\Pool;

class MediaExtension extends \Twig_Extension
{
    protected $mediaService;
    protected $resources = array();
    protected $mediaManager;
    protected $cdnPath;

    /**
     * @param Pool             $mediaService
     * @param ManagerInterface $mediaManager
     * @param string           $cdnPath
     * @param string           $rootDir
     */
    public function __construct(Pool $mediaService, ManagerInterface $mediaManager, $cdnPath, $rootDir)
    {
        $this->mediaService = $mediaService;
        $this->mediaManager = $mediaManager;
        $this->cdnPath = $cdnPath;
        $this->rootDir = $rootDir;
    }

    public function getFunctions() {
        return array(
            'path_to_media_by_array' => new \Twig_Function_Method($this, 'pathToMediaByArray'),
        );
    }

    /**
     * @param mixed $media
     *
     * @return null|\Sonata\MediaBundle\Model\MediaInterface
     */
    private function getMedia($mediaId)
    {
        $media = $this->mediaManager->findOneBy(array('id' => $mediaId));

        if (!$media instanceof MediaInterface) {
            return false;
        }

        if ($media->getProviderStatus() !== MediaInterface::STATUS_OK) {
            return false;
        }

        return $media;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @param string                                   $format
     *
     * @return string
     */
    public function pathToMediaByArray($media, $format)
    {
        if (!$media) {
             return '';
        }

        $format = $this->getFormatName($media, $format);
        $path = $this->generatePublicUrl($media, $format);

        return $this->getPath($path);
    }

    public function getPath($relativePath)
    {
        return sprintf('%s/%s', rtrim($this->cdnPath, '/'), ltrim($relativePath, '/'));
    }

    public function generatePublicUrl($media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = sprintf(
                '%s/thumb_%s_%s.%s',
                $this->generatePath($media),
                $media['id'],
                $format,
                pathinfo($media['providerReference'], PATHINFO_EXTENSION)
            );
        }
        return $path;
    }

    public function getReferenceImage($media)
    {
        return sprintf('%s/%s',
            $this->generatePath($media),
            $media['providerReference']//param ProviderReference
        );
    }

    public function generatePath($media)
    {
        $firstLevel = 100000;
        $secondLevel = 1000;
        $rep_first_level = (int) ($media['id'] / $firstLevel);//param Id
        $rep_second_level = (int) (($media['id'] - ($rep_first_level * $firstLevel)) / $secondLevel);

        return sprintf('%s/%04s/%02s', $media['context'], $rep_first_level + 1, $rep_second_level + 1);//param Context
    }

    public function getFormatName($media, $format)
    {
        if ($format == 'admin') {
            return 'admin';
        }

        if ($format == 'reference') {
            return 'reference';
        }

        $baseName = $media['context'].'_';
        if (substr($format, 0, strlen($baseName)) == $baseName) {
            return $format;
        }

        return $baseName.$format;
    }

    /**
     * @return \Sonata\MediaBundle\Provider\Pool
     */
    public function getMediaService()
    {
        return $this->mediaService;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'espace_media';
    }
}
