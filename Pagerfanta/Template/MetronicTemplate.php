<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CoreBundle\Pagerfanta\Template;

use Pagerfanta\View\Template\Template;
use Pagerfanta\View\ViewInterface;
use Positibe\Bundle\CoreBundle\Pagerfanta\View\MetronicView;


/**
 * Class MetronicTemplate
 * @package Positibe\Bundle\CoreBundle\Pagerfanta\Template
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MetronicTemplate extends Template
{
    /** @var  MetronicView */
    private $view;

    public function setView(ViewInterface $view)
    {
        $this->view = $view;
    }

    static protected $defaultOptions = array(
        'prev_message' => '&larr; Antes',
        'next_message' => 'Next &rarr;',
        'dots_message' => '&hellip;',
        'active_suffix' => '',
        'css_container_class' => 'pagination',
        'css_prev_class' => 'prev',
        'css_next_class' => 'next',
        'css_disabled_class' => 'disabled',
        'css_dots_class' => 'disabled',
        'css_active_class' => 'active',
        'page' => 'Página',
    );

    public function container()
    {
        return sprintf(
            '<div class="%s"><form method="get" action="">%s %%pages%%</form></div>',
            $this->option('css_container_class'),
            $this->option('page')
        );
    }

    public function page($page)
    {
        return;
    }

    public function previousDisabled()
    {
        return '<a href="#" class="btn btn-sm default prev disabled" title="Prev"><i class="fa fa-angle-left"></i></a>';
    }

    public function previousEnabled($page)
    {
        $href = $this->generateRoute($page);

        return sprintf(
            '<a href="%s" class="btn btn-sm default prev" title="Prev"><i class="fa fa-angle-left"></i></a>',
            $href
        );
    }

    public function nextDisabled()
    {
        return '<a href="#" class="btn btn-sm default next disabled" title="Next"><i class="fa fa-angle-right"></i></a>';
    }

    public function nextEnabled($page)
    {
        $href = $this->generateRoute($page);

        return sprintf(
            '<a href="%s" class="btn btn-sm default next" title="Next"><i class="fa fa-angle-right"></i></a>',
            $href
        );
    }

    public function first()
    {
        return $this->page(1);
    }

    public function last($page)
    {
        return $this->page($page);
    }

    public function current($page)
    {
        $text = trim($page . ' ' . $this->option('active_suffix'));

        return sprintf(
            '<input type="number" class="pagination-panel-input form-control input-mini input-inline input-sm" min="%s" max="%s" name="page" value="%s"
                    style="text-align:center; margin: 0 5px; padding: 5px 0 5px 15px">',
            1,
            $this->view->getPagerfanta()->getNbPages(),
            $text
        );
    }

    public function separator()
    {
        return;
    }

    /**
     * Renders a given page with a specified text.
     *
     * @param int $page
     * @param string $text
     *
     * @return string
     */
    public function pageWithText($page, $text)
    {
        return;
    }


} 