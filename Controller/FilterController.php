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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class FilterController
 * @package Positibe\Bundle\CoreBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class FilterController extends Controller
{
    public function getEntityFilterAction(
        Request $request,
        $class = null,
        $criteria = [],
        $selected = null,
        $field = null,
        $presentationField = null,
        $emptyText = 'Todos'
    ) {
        try {
            $entities = $this->get('doctrine.orm.entity_manager')->getRepository(
                $request->get('class', $class)
            )->findBy($request->get('criteria', $criteria));
        } catch (\Exception $e) {
            $entities = new ArrayCollection();
        }

        return $this->render(
            '@PositibeCore/Filter/_filter_list_entity.html.twig',
            array(
                'empty_text' => $request->get('emptyText', $emptyText),
                'entities' => $entities,
                'field_selected' => $request->get('selected', $selected),
                'field' => $request->get('field', $field),
                'presentationField' => $request->get('presentationField', $presentationField),
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
                'field_selected' => $selected,
            )
        );
    }

    public function getEnumFilterAction(Request $request, $type, $selected)
    {
        if ($this->container->has('positibe.repository.enum')) {
            $entities = $this->container->get('positibe.repository.enum')->findEnums($type);
        } else {
            $entities = new ArrayCollection();
        }

        return $this->render(
            '@PositibeCore/Filter/_filter_list_enum.html.twig',
            array(
                'empty_text' => $request->get('emptyText', 'Todos'),
                'entities' => $entities,
                'field_selected' => $selected,
            )
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function apiEntityAjaxAction(Request $request)
    {
        try {
            $classMetadata = $this->get('doctrine.orm.entity_manager')->getMetadataFactory()->getMetadataFor(
                $request->get('entity')
            );
            $fieldText = explode(',', $request->get('fieldText'));
            if (count($fieldText) > 1) {
                $contact = [];
                foreach ($fieldText as $field) {
                    $contact[] = sprintf('u.%s', $field);
                    $contact[] = '\' - \'';
                }
                array_pop($contact);
                $fieldText = sprintf('CONCAT(%s)', join(',', $contact));
            } else {
                $fieldText = sprintf('u.%s', $fieldText[0]);
            }
            $extra = '';
            $whereSet = false;
            if (($dependField = $request->query->get('depend_field')) && ($dependValue = $request->query->get(
                    'depend_value'
                ))) {
                $extra .= sprintf(" WHERE u.%s = '%s'", $dependField, $dependValue);
                $whereSet = true;
            }
            if ($filter = $request->query->get('query')) {
                $extra .= sprintf(" %s %s LIKE '%s'", $whereSet ? 'AND' : 'WHERE', $fieldText, $filter."%");
            }


            $order = explode(',', $request->get('order'));
            if (count($order) === 2) {
                $extra .= sprintf(' ORDER BY u.%s %s', $order[0], $order[1]);
            } else {
                $extra .= ' ORDER BY text';
            }
            $dql = sprintf(
                'SELECT u.%s id,%s text FROM %s u %s',
                $classMetadata->getIdentifier()[0],
                $fieldText,
                $classMetadata->getName(),
                $extra
            );


            $query = $this->get('doctrine.orm.entity_manager')->createQuery($dql);

            $entities = $query->getArrayResult();
        } catch (\Exception $e) {
            $entities = [];
        }

        return new JsonResponse($entities);
    }
} 