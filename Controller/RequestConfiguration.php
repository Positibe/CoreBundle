<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CoreBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration as SyliusRequestConfiguration;

/**
 * Class RequestConfiguration
 * @package Positibe\Bundle\CoreBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class RequestConfiguration extends SyliusRequestConfiguration
{
    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function getTemplate($name)
    {
        $template = $this->parameters->get('template', $this->getDefaultTemplate($name.'.html'));
        if (null === $template) {
            throw new \RuntimeException(
                sprintf('Could not resolve template for resource "%s".', $this->metadata->getAlias())
            );
        }

        return $template;
    }

    /**
     * Get redirect referer, This will detected by configuration
     * If not exists, The `referrer` from headers will be used.
     *
     * @return null|string
     */
    public function getRedirectReferer()
    {
        $redirect = $this->parameters->get('redirect');
        $referer = $this->request->headers->get('referer');

        if ($forceRedirect = $this->request->query->get('redirect')) {
            return $forceRedirect;
        }

        if (!is_array($redirect) || empty($redirect['referer'])) {
            return $referer;
        }

        if ($redirect['referer'] === true) {
            return $referer;
        }
    }

}