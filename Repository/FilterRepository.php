<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmfBundle\Repository;

use Doctrine\ORM\QueryBuilder;


/**
 * Class FilterRepository
 * @package Positibe\Bundle\CmfBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class FilterRepository
{
    /**
     * Filter a field with LIKE sql function
     *
     * @param QueryBuilder $queryBuilder
     * @param $criteria
     * @param $field
     * @return mixed
     */
    public static function filterLike(QueryBuilder $queryBuilder, $criteria, $field)
    {
        if (isset($criteria[$field])) {
            if (!empty($criteria[$field])) {
                $queryBuilder->andWhere(sprintf('o.%s LIKE :%s', $field, $field))->setParameter(
                  $field,
                  '%' . $criteria[$field] . '%'
                );
            }
            unset($criteria[$field]);
        }

        return $criteria;
    }

    /**
     * Filter an enumerator field from PositibeEnumBundle
     *
     * @param QueryBuilder $queryBuilder
     * @param $criteria
     * @param $field
     * @param $enumTypeName
     * @return mixed
     */
    public static function filterEnum(QueryBuilder $queryBuilder, $criteria, $field, $enumTypeName)
    {
        if (isset($criteria[$field])) {
            if (!empty($criteria[$field])) {

                $queryBuilder
                  ->join('o.' . $field, 'enum')
                  ->join('enum.type', 'enumType')
                  ->andWhere('enumType.name = :enumType')
                  ->andWhere(sprintf('enum.text LIKE :%s', $field))
                  ->setParameter($field, '%' . $criteria[$field] . '%')
                  ->setParameter('enumType', $enumTypeName);
            }
            unset($criteria[$field]);
        }

        return $criteria;
    }

    /**
     * Filter a datetime field by a range
     *
     * @param QueryBuilder $queryBuilder
     * @param $criteria
     * @param $field
     * @param $fieldFrom
     * @param $fieldTo
     * @return mixed
     */
    public static function filterRangeDate(QueryBuilder $queryBuilder, $criteria, $field, $fieldFrom, $fieldTo)
    {
        if (isset($criteria[$fieldFrom]) || isset($criteria[$fieldTo]) && $criteria[$fieldTo] != '') {
            if (!empty($criteria[$fieldFrom])) {
                $from = $criteria[$fieldFrom] . ' 00:00:00';
                $queryBuilder
                  ->andWhere($queryBuilder->expr()->gte(sprintf('o.%s', $field), sprintf(':%s', $fieldFrom)))
                  ->setParameter($fieldFrom, \DateTime::createFromFormat('d/m/Y H:i:s', $from));
            }
            unset($criteria[$fieldFrom]);
            if (!empty($criteria[$fieldTo])) {
                $to = $criteria[$fieldTo] . ' 23:59:59';
                $queryBuilder
                  ->andWhere($queryBuilder->expr()->lte(sprintf('o.%s', $field), sprintf(':%s', $fieldTo)))
                  ->setParameter($fieldTo, \DateTime::createFromFormat('d/m/Y H:i:s', $to));
            }
            unset($criteria[$fieldTo]);
        }

        return $criteria;
    }

    /**
     * Filter a field thar has its to one relation
     *
     * @param QueryBuilder $queryBuilder
     * @param $criteria
     * @param $field
     * @param $toOneField
     * @param bool $isJoined
     * @return mixed
     */
    public static function filterToOneField(
      QueryBuilder $queryBuilder,
      $criteria,
      $field,
      $toOneField,
      $isJoined = false
    ) {
        if (isset($criteria[$field])) {
            if (!empty($criteria[$field])) {
                if (!$isJoined) {
                    $queryBuilder
                      ->join(sprintf('o.%s', $toOneField), $toOneField);
                }
                $queryBuilder
                  ->andWhere(sprintf('%s.%s LIKE :%s', $toOneField, $field, $toOneField . '_' . $field))
                  ->setParameter($toOneField . '_' . $field, '%' . $criteria[$field] . '%');
            }
            unset($criteria[$field]);
        }

        return $criteria;
    }

} 