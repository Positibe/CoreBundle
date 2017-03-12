<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CoreBundle\EventListener;

use Stof\DoctrineExtensionsBundle\EventListener\LocaleListener as StofLocaleListener;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class LocaleListener
 * @package Positibe\Bundle\CmfRoutingExtraBundle\EventListener
 *
 * @todo Pasar a la configuración
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LocaleListener extends StofLocaleListener
{
    public static function getSubscribedEvents()
    {
        return array(KernelEvents::REQUEST => array(array('onKernelRequest', 90)));
    }

    /**
     * Set the translation listener locale from the request.
     *
     * This method should be attached to the kernel.request event.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->attributes->has('_locale')) {
            $request->setLocale($request->attributes->get('_locale'));
        }
        parent::onKernelRequest($event);
    }
} 