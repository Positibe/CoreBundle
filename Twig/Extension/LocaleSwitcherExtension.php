<?php
/**
 * This file is part of the LuneticsLocaleBundle package.
 *
 * <https://github.com/lunetics/LocaleBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */
namespace Positibe\Bundle\CmfBundle\Twig\Extension;

use Positibe\Bundle\CmfBundle\Locale\Switcher\TargetInformationBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LocaleSwitcherExtension
 * @package Positibe\Bundle\CmfBundle\Twig\Extension
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LocaleSwitcherExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @return array The added functions
     */
    public function getFunctions()
    {
        return array(
            'positibe_locale_switcher' => new \Twig_Function_Method(
                $this,
                'renderSwitcher',
                array('is_safe' => array('html'))
            )
        );
    }

    /**
     *
     * @return string The name of the extension
     */
    public function getName()
    {
        return 'positibe_locale_switcher';
    }

    /**
     * @param null $route
     * @param array $parameters
     * @param null $template
     * @return string
     * @throws \Exception
     */
    public function renderSwitcher($route = null, $parameters = array(), $template = null)
    {
        $showCurrentLocale = $this->container->getParameter('lunetics_locale.switcher.show_current_locale');
        $allowedLocales = $this->container->get('lunetics_locale.allowed_locales_provider')->getAllowedLocales();
        $request = $this->container->get('request_stack')->getMasterRequest();
        $router = $this->container->get('router');

        $builder = new TargetInformationBuilder($request, $router, $allowedLocales, $showCurrentLocale);
        $infos = $builder->getTargetInformations($route, $parameters);

        return $this->container->get('lunetics_locale.switcher_helper')->renderSwitch($infos, $template);

    }
}
