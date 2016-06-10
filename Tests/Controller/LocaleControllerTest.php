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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * Class LocaleControllerTest
 * @package Positibe\Bundle\CmfBundle\Tests\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class LocaleControllerTest extends WebTestCase
{
    public function testChangeLocale()
    {
        $client = static::createClient();

        $locales = $client->getContainer()->getParameter('locales');

        foreach ($locales as $locale) {
            $client->request(
              'GET',
              static::$kernel->getContainer()->get('router')->generate(
                'positibe_change_locale',
                array('newLocale' => $locale)
              )
            );

            $this->assertEquals(
              $client->getRequest()->getLocale(),
              $locale
            );

        }
    }
} 