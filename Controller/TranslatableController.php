<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmfBundle\Controller;

use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractPage;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeReferrersInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as SyliusResourceController;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TranslatableController
 * @package Positibe\Bundle\CmfBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TranslatableController extends SyliusResourceController
{
    /**
     * Load the correct locale for seo and menus depend of data_locale http parameter
     *
     * @param Request $request
     * @param array $criteria
     * @return object|void
     */
    public function findOr404(Request $request, array $criteria = array())
    {
        /** @var AbstractPage $page */
        $page = parent::findOr404($request, $criteria);

        if ($page instanceof TranslatableInterface && $dataLocale = $request->get('data_locale')) {
            $page->setLocale($dataLocale);

            if ($page instanceof SeoAwareInterface && $seoMetadata = $page->getSeoMetadata()) {
                $seoMetadata->setLocale($dataLocale);
                $this->get('doctrine.orm.entity_manager')->refresh($seoMetadata);
            }

            if ($page instanceof MenuNodeReferrersInterface) {
                foreach ($page->getMenuNodes() as $menu) {
                    $menu->setLocale($dataLocale);
                    $this->get('doctrine.orm.entity_manager')->refresh($menu);
                }
            }

            $this->get('doctrine.orm.entity_manager')->refresh($page);
        }

        return $page;
    }
} 