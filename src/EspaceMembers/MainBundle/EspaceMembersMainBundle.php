<?php

namespace EspaceMembers\MainBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use EspaceMembers\MainBundle\DependencyInjection\Compiler\OverrideMediaGalleryCompilerPass;

class EspaceMembersMainBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideMediaGalleryCompilerPass());
    }
}
