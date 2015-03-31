<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * OverrideMediaGalleryCompilerPass
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class OverrideMediaGalleryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sonata.media.admin.gallery');
        if ($definition && $definition->hasTag('sonata.admin')) {
            $tags = $definition->getTag('sonata.admin');
            $tags[0]['show_in_dashboard'] = false;

            $definition->clearTag('sonata.admin');
            $definition->addTag('sonata.admin', $tags[0]);
        }
    }
}
