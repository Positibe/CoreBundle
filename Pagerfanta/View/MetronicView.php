<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CoreBundle\Pagerfanta\View;

use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Pagerfanta\View\DefaultView;
use Positibe\Bundle\CoreBundle\Pagerfanta\Template\MetronicTemplate;


/**
 * Class MetronicView
 * @package Positibe\Bundle\CoreBundle\Pagerfanta\View
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MetronicView extends DefaultView
{
    protected $pagerfanta;

    /**
     * @return Pagerfanta
     */
    public function getPagerfanta()
    {
        return $this->pagerfanta;
    }

    /**
     * {@inheritdoc}
     */
    public function render(PagerfantaInterface $pagerfanta, $routeGenerator, array $options = array())
    {
        $this->pagerfanta = $pagerfanta;
        return parent::render($pagerfanta, $routeGenerator, $options);
    }

    protected function createDefaultTemplate()
    {
        $template = new MetronicTemplate();
        $template->setView($this);

        return $template;
    }

    protected function previousMessageOption()
    {
        return 'prev_message';
    }

    protected function nextMessageOption()
    {
        return 'next_message';
    }

    protected function buildPreviousMessage($text)
    {
        return sprintf('&larr; %s', $text);
    }

    protected function buildNextMessage($text)
    {
        return sprintf('%s &rarr;', $text);
    }

    /**
     * Returns the canonical name.
     *
     * @return string The canonical name.
     */
    public function getName()
    {
        return 'positibe_cmf_metronic';
    }
} 