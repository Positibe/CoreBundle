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

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class FilterController
 * @package Positibe\Bundle\CoreBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class FilterController extends Controller
{
    public function getEntityFilterAction($class, $selected, $field = null, $presentationField = null)
    {
        try {
            $entities = $this->get('doctrine.orm.entity_manager')->getRepository($class)->findAll();
        } catch (\Exception $e) {
            $entities = new ArrayCollection();
        }

        return $this->render(
            '@PositibeCore/Filter/_filter_list_entity.html.twig',
            array(
                'entities' => $entities,
                'field_selected' => $selected,
                'field' => $field,
                'presentationField' => $presentationField
            )
        );
    }

    public function getMethodFilterAction($class, $method, $selected)
    {
        if (method_exists($class, $method)) {
            $entities = $class::$method();
        } else {
            $entities = array();
        }

        return $this->render(
            '@PositibeCore/Filter/_filter_list_method.html.twig',
            array(
                'entities' => $entities,
                'field_selected' => $selected
            )
        );
    }

    public function getEnumFilterAction($type, $selected)
    {
        if ($this->container->has('positibe.repository.enum')) {
            $entities = $this->container->get('positibe.repository.enum')->findEnums($type);
        } else {
            $entities = new ArrayCollection();
        }

        return $this->render(
            '@PositibeCore/Filter/_filter_list_enum.html.twig',
            array(
                'entities' => $entities,
                'field_selected' => $selected
            )
        );
    }
} 