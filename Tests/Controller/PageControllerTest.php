<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmfBundle\Tests\Controller;

use Positibe\Bundle\TestingBundle\Test\BaseCrudTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * Class PageControllerTest
 * @package Positibe\Bundle\CmfBundle\Tests\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageControllerTest extends BaseCrudTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->server = 'http://localhost/positibe-project/web/app.php/';
        $this->server = '';
    }


    public function getNewUrl()
    {
        return $this->get('router')->generate('positibe_page_create', array(), UrlGeneratorInterface::RELATIVE_PATH);
    }

    public function createFormData()
    {
        return array(
          'title' => 'Página para testing',
          'body' => 'Esto es una página para testing',
        );
    }

    public function getResourceContains()
    {
        return "Página para testing";
    }

    public function getEditLink()
    {
        return $this->get('router')->generate('positibe_page_update');
    }

    public function createEditFormData()
    {
        return array(
          'title' => 'Página para testing editada',
          'body' => 'Esto es una página para testing',
        );
    }

    public function getEditedResourceContains()
    {
        return "Página para testing editada";
    }

    public function getIndexUrl()
    {
        return $this->get('router')->generate('positibe_page_index');
    }

    public function getResourceListContains()
    {
        return "Página para testing editada";
    }

} 