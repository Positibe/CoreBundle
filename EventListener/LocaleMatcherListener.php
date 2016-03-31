<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmfBundle\EventListener;

use Lunetics\LocaleBundle\Event\FilterLocaleSwitchEvent;
use Lunetics\LocaleBundle\EventListener\LocaleListener as LuneticsLocaleListener;
use Lunetics\LocaleBundle\LocaleBundleEvents;
use Lunetics\LocaleBundle\LocaleGuesser\LocaleGuesserManager;
use Lunetics\LocaleBundle\Matcher\BestLocaleMatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;


/**
 * Class LocaleMatcherListener
 * @package Positibe\Bundle\CmfBundle\EventListener
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LocaleMatcherListener extends LuneticsLocaleListener {
    /**
     * @var string Default framework locale
     */
    private $defaultLocale;

    /**
     * @var LocaleGuesserManager
     */
    private $guesserManager;

    /**
     * @var BestLocaleMatcher
     */
    private $bestLocaleMatcher;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @var boolean
     */
    private $disableVaryHeader = false;

    /**
     * @var string
     */
    private $excludedPattern;

    /**
     * Construct the guessermanager
     *
     * @param string $defaultLocale
     * @param LocaleGuesserManager $guesserManager
     * @param BestLocaleMatcher $bestLocaleMatcher
     */
    public function __construct($defaultLocale = 'en', LocaleGuesserManager $guesserManager, BestLocaleMatcher $bestLocaleMatcher = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->guesserManager = $guesserManager;
        $this->bestLocaleMatcher = $bestLocaleMatcher;
    }

    /**
     * Called at the "kernel.request" event
     *
     * Call the LocaleGuesserManager to guess the locale
     * by the activated guessers
     *
     * Sets the identified locale as default locale to the request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $request->setDefaultLocale($this->defaultLocale);

        if (!$event->isMasterRequest() || ($this->excludedPattern && preg_match(sprintf('#%s#', $this->excludedPattern), $request->server->get('PATH_INFO')))) {
            return;
        }

        $manager = $this->guesserManager;
        $locale = $manager->runLocaleGuessing($request);

        if ($locale && $this->bestLocaleMatcher) {
            $locale = $this->bestLocaleMatcher->match($locale);
        }

        if ($locale) {
            //Setting [ %s ] as locale for the (Sub-)Request';
            $request->setLocale($locale);
            $request->attributes->set('_locale', $locale);

            if (($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST || $request->isXmlHttpRequest())
                && ($manager->getGuesser('session') || $manager->getGuesser('cookie'))
            ) {
                $localeSwitchEvent = new FilterLocaleSwitchEvent($request, $locale);
                $this->dispatcher->dispatch(LocaleBundleEvents::onLocaleChange, $localeSwitchEvent);
            }
        }
    }

    /**
     * This Listener adds a vary header to all responses.
     *
     * @param FilterResponseEvent $event
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onLocaleDetectedSetVaryHeader(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        if (!$this->disableVaryHeader) {
            $response->setVary('Accept-Language', false);
        }
        return $response;
    }
    /**
     * DI Setter for the EventDispatcher
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param boolean $disableVaryHeader
     */
    public function setDisableVaryHeader($disableVaryHeader)
    {
        $this->disableVaryHeader = $disableVaryHeader;
    }

    /**
     * @param string $excludedPattern
     */
    public function setExcludedPattern($excludedPattern)
    {
        $this->excludedPattern = $excludedPattern;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the Router to have access to the _locale and before the Symfony LocaleListener
            KernelEvents::REQUEST => array(array('onKernelRequest', 100)),
            KernelEvents::RESPONSE => array('onLocaleDetectedSetVaryHeader')
        );
    }
} 