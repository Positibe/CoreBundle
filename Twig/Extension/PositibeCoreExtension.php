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
        return [
            new \Twig_SimpleFunction('go_back', [$this, 'goBack']),
            new \Twig_SimpleFunction('loggable', [$this, 'loggable']),
            new \Twig_SimpleFunction(
                'grid_render_sorting',
                [$this, 'renderSortingLink'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @return array
     */
    public function getTests()
    {
        return [
            'date' => new \Twig_SimpleTest('date', [$this, 'isDate']),
        ];
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

    /**
     * @param \Twig_Environment $twig
     * @param $property
     * @param null $label
     * @param string $order
     * @param array $options
     * @return string
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function renderSortingLink(
        \Twig_Environment $twig,
        $property,
        $label = null,
        $order = 'asc',
        array $options = []
    ) {
        if (null === $label) {
            $label = $property;
        }

        if ('asc' !== $order && 'desc' !== $order) {
            $order = 'asc';
        }

        $options = $this->getOptions($options, $this->container->getParameter('positibe_core.sorting_template'));
        $sorting = $this->getRequest()->query->get('sorting', ['id' => 'asc']);
        $currentOrder = null;

        if (isset($sorting[$property])) {
            $currentOrder = $sorting[$property];
            $order = 'asc' === $sorting[$property] ? 'desc' : 'asc';
        }

        $url = $this->container->get('router')->generate(
            $this->getRouteName($options['route']),
            $this->getRouteParams(
                ['sorting' => [$property => $order]],
                $options['route_params']
            )
        );

        return $twig->render(
            $options['template'],
            [
                'url' => $url,
                'label' => $label,
                'icon' => $property == key($sorting),
                'currentOrder' => $currentOrder,
            ]
        );
    }

    /**
     * @param array $options
     * @param string $defaultTemplate
     *
     * @return array
     */
    private function getOptions(array $options, $defaultTemplate)
    {
        if (!array_key_exists('template', $options)) {
            $options['template'] = $defaultTemplate;
        }

        if (!array_key_exists('route', $options)) {
            $options['route'] = null;
        }

        if (!array_key_exists('route_params', $options)) {
            $options['route_params'] = [];
        }

        return $options;
    }

    /**
     * @param null|string $route
     *
     * @return mixed|null
     */
    private function getRouteName($route = null)
    {
        return null === $route ? $this->getRequest()->attributes->get('_route') : $route;
    }


    /**
     * @param array $params
     * @param array $default
     *
     * @return array
     */
    private function getRouteParams(array $params = [], array $default = [])
    {
        return array_merge(
            $this->getRequest()->query->all(),
            $this->getRequest()->attributes->get('_route_params'),
            array_merge($params, $default)
        );
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    private function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = $this->container->get('request_stack')->getMasterRequest();
        }

        return $this->request;
    }


    /**
     *
     * @return string The name of the extension
     */
    public function getName()
    {
        return 'positibe_core_extension';
    }
}
