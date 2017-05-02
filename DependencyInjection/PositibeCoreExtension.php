<?php

namespace Positibe\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PositibeCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        //$config = $this->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('kernel.bundles');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('twig_extension_services.yml');
        $loader->load('services.yml');

        if(isset($bundles['LuneticsLocalebundle']))
        {
            $this->addClassesToCompile(array(
                'Lunetics\\LocaleBundle\\EventListener\\LocaleListener',
                'Lunetics\\LocaleBundle\\LocaleGuesser\\LocaleGuesserManager',
                'Lunetics\\LocaleBundle\\Matcher\\DefaultBestLocaleMatcher',
                'Lunetics\\LocaleBundle\\EventListener\\LocaleUpdateListener',
                'Lunetics\\LocaleBundle\\Cookie\\LocaleCookie',
                'Lunetics\\LocaleBundle\\Session\\LocaleSession',
                'Stof\\DoctrineExtensionsBundle\\EventListener\\LocaleListener',
                'Positibe\\Bundle\\CoreBundle\\EventListener\\LocaleListener',
              ));
        }
    }
}
