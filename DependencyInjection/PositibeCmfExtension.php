<?php

namespace Positibe\Bundle\CmfBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PositibeCmfExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('kernel.bundles');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        if (isset($bundles['CmfSeoBundle'])) {
            $loader->load('cmf_seo_extractor_services.yml');
        }

        $loader->load('twig_extension_services.yml');
        $loader->load('services.yml');

        $this->addClassesToCompile(array(
                'Lunetics\\LocaleBundle\\EventListener\\LocaleListener',
                'Lunetics\\LocaleBundle\\LocaleGuesser\\LocaleGuesserManager',
                'Lunetics\\LocaleBundle\\Matcher\\DefaultBestLocaleMatcher',
                'Lunetics\\LocaleBundle\\EventListener\\LocaleUpdateListener',
                'Lunetics\\LocaleBundle\\Cookie\\LocaleCookie',
                'Lunetics\\LocaleBundle\\Session\\LocaleSession',
                'Positibe\\Bundle\\CmfBundle\\EventListener\\LocaleMatcherListener',
                'Stof\\DoctrineExtensionsBundle\\EventListener\\LocaleListener',
                'Positibe\\Bundle\\CmfBundle\\EventListener\\LocaleListener',
                'Symfony\\Cmf\\Bundle\\CoreBundle\\EventListener\\PublishWorkflowListener',
                'Symfony\Cmf\Bundle\SeoBundle\EventListener\ContentListener'
            ));
    }
}
