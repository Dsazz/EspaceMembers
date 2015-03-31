<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Twig;

use Sonata\MediaBundle\Twig\TokenParser\MediaTokenParser;
use Sonata\MediaBundle\Twig\TokenParser\ThumbnailTokenParser;
use Sonata\MediaBundle\Twig\TokenParser\PathTokenParser;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\MediaBundle\Twig\Extension\MediaExtension as SonataMediaExtension;

/**
 * MediaExtension
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class MediaExtension extends \Twig_Extension
{
    protected $mediaService;
    protected $resources = array();
    protected $mediaManager;
    protected $cdnPath;

    /**
     * @param Pool             $mediaService
     * @param ManagerInterface $mediaManager
     * @param MediaExtension   $sonataMediaExtension
     * @param string           $cdnPath
     */
    public function __construct(
        Pool $mediaService,
        ManagerInterface $mediaManager,
        SonataMediaExtension $sonataMediaExtension,
        $cdnPath,
    )
    {
        $this->mediaService = $mediaService;
        $this->mediaManager = $mediaManager;
        $this->sonataMediaExtension = $sonataMediaExtension;
        $this->cdnPath = $cdnPath;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('path_to_media', [$this, 'getPathToMediaByArray']),
        );
    }

    /**
     * @param mixed  $media
     * @param string $format
     *
     * @return string
     */
    public function getPathToMediaByArray($media, $format)
    {
        if (!$media) {
             return '';
        }

        if ($media instanceof MediaInterface) {
            return $this->sonataMediaExtension->path($media, $format);
        }

        $format = $this->getFormatName($media, $format);
        $path = $this->generatePublicUrl($media, $format);

        return $this->getPath($path);
    }

    public function getPath($relativePath)
    {
        return sprintf('%s/%s', rtrim($this->cdnPath, '/'), ltrim($relativePath, '/'));
    }

    /**
     * @param mixed  $media
     * @param string $format
     *
     * @return string
     */
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

    /**
     * @param mixed  $media
     *
     * @return string
     */
    public function getReferenceImage($media)
    {
        return sprintf('%s/%s',
            $this->generatePath($media),
            $media['providerReference']//param ProviderReference
        );
    }

    /**
     * @param mixed  $media
     *
     * @return string
     */
    public function generatePath($media)
    {
        $firstLevel = 100000;
        $secondLevel = 1000;
        $rep_first_level = (int) ($media['id'] / $firstLevel);//param Id
        $rep_second_level = (int) (($media['id'] - ($rep_first_level * $firstLevel)) / $secondLevel);

        return sprintf('%s/%04s/%02s', $media['context'], $rep_first_level + 1, $rep_second_level + 1);//param Context
    }

    /**
     * @param mixed  $media
     * @param string $format
     *
     * @return string
     */
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
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'espace_media_extension';
    }
}
