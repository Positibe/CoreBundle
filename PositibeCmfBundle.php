<?php

namespace Positibe\Bundle\CmfBundle;

use Positibe\Bundle\CmfBundle\DependencyInjection\Compiler\PositibeCmfCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeCmfBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new PositibeCmfCompilerPass());
    }
}
