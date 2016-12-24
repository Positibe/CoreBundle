<?php
/**
 * This file is part of the LuneticsLocaleBundle package.
 *
 * <https://github.com/lunetics/LocaleBundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that is distributed with this source code.
 */
namespace Positibe\Bundle\CoreBundle\Twig\Extension;

use Positibe\Bundle\CoreBundle\Locale\Switcher\TargetInformationBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LocaleSwitcherExtension
 * @package Positibe\Bundle\CoreBundle\Twig\Extension
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PositibeCoreExtension extends \Twig_Extension
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
            ),
            'go_back' => new \Twig_Function_Method(
                $this,
                'goBack',
                array('is_safe' => array('html'))
            ),
            'loggable' => new \Twig_Function_Method(
                $this,
                'loggable',
                array('is_safe' => array('html'))
            ),
        );
    }

    public function getTests()
    {
        return [
            'date' => new \Twig_SimpleTest(
                'date',
                'Positibe\Bundle\CoreBundle\Twig\Extension\PositibeThemeExtension::isDate'
            ),
        ];
    }

    /**
     *
     * @return string The name of the extension
     */
    public function getName()
    {
        return 'positibe_theme_twig_extension';
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

    /**
     * @param $route
     * @param array $parameters
     * @return mixed|string
     */
    public function goBack($route, $parameters = array())
    {
        if (isset($this->goBackUri)) {
            return $this->goBackUri;
        }

        $request = $this->container->get('request_stack')->getCurrentRequest();

        $login = $request->server->get('REQUEST_SCHEME').'://'.$request->server->get('HTTP_HOST').$request->getBaseUrl(
            ).'/login';
        $current = $request->getUri();
        $referer = $request->server->get('HTTP_REFERER');
        if (strstr($referer, $login) || $referer === $current) {
            $referer = $this->container->get('router')->generate($route, $parameters);
        }
        $this->goBackUri = $referer;

        return $referer;
    }

    /**
     * @param $entity
     * @param array $options
     * @param string $template
     * @return string
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function loggable($entity, $options = [], $template = 'PositibeCoreBundle::_loggable.html.twig')
    {
        $logEntryRepository = $this->container->get('doctrine.orm.entity_manager')->getRepository(
            'Gedmo\Loggable\Entity\LogEntry'
        );
        $logs = $logEntryRepository->getLogEntries($entity);

        return $this->container->get('templating')->render(
            $template,
            array_merge(['logs' => $logs, 'trans_prefix' => ''], $options)
        );
    }

    public function isDate($date)
    {
        return $date instanceof \DateTime;
    }
}
