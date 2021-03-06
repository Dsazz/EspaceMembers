<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sonata\MediaBundle\Entity\BaseMedia as Media;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Mapping as ORM;

/**
 * MediaListener
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class MediaListener
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /** @ORM\prePersist */
    public function prePersistHandler(Media $media, LifecycleEventArgs $event)
    {
        if ($media->getContext() === 'lesson') {
            $audio = $this->container->get('getid3')->analyze($media->getBinaryContent());
            $media->setPlaytime(
                isset($audio['playtime_string']) ? $audio['playtime_string'] : ''
            );
        }
    }
}
