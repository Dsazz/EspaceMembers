<?php
namespace EspaceMembers\MainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideMediaGalleryCompilerPass implements CompilerPassInterface
{
    public function process( ContainerBuilder $container )
    {
        $definition = $container->getDefinition( 'sonata.media.admin.gallery' );
        if ( $definition ) {
            if ( $definition->hasTag( 'sonata.admin' ) ) {
                $tags                             = $definition->getTag( 'sonata.admin' );
                $tags[ 0 ][ 'show_in_dashboard' ] = false;
                $definition->clearTag( 'sonata.admin' );
                $definition->addTag( 'sonata.admin', $tags[ 0 ] );
            }
        }
    }
}
