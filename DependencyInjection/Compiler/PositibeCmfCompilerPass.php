<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;


/**
 * Class PositibeCoreCompilerPass
 * @package Positibe\Bundle\OrmContentBundle\DependencyInjection\Compiler
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PositibeCoreCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $sluggableListener = $container->getDefinition('stof_doctrine_extensions.listener.sluggable');
        $sluggableListener->addMethodCall('addManagedFilter', array('softdeleteable'));

        $container->setParameter(
          'lunetics_locale.request_listener.class',
          'Positibe\Bundle\CoreBundle\EventListener\LocaleMatcherListener'
        );

        //@fixme to remove in Sylius 0.18 or higher
        $container->setParameter(
          'sylius.resource_controller.single_resource_provider.class',
          'Positibe\Bundle\CoreBundle\Controller\SingleResourceProvider'
        );

        //@fixme to remove in Sylius 0.18 or higher
        $container->setParameter(
          'sylius.resource_controller.request_configuration.class',
          'Positibe\Bundle\CoreBundle\Controller\RequestConfiguration'
        );
    }

} 