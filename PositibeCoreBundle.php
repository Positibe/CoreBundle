<?php

namespace Positibe\Bundle\CoreBundle;

use Positibe\Bundle\CoreBundle\DependencyInjection\Compiler\PositibeCoreCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new PositibeCoreCompilerPass());
    }
}
