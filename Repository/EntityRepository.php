<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CoreBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as SyliusEntityReporitoy;

/**
 * Class EntityRepository
 * @package Positibe\Bundle\CoreBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EntityRepository extends SyliusEntityReporitoy
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [])
    {
        foreach ($criteria as $property => $value) {
            if (!in_array($property, $this->_class->getFieldNames())) {
                continue;
            }

            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $orNull = false;

                for ($i = 0; $i < count($value); $i++) {
                    if ($value[$i] === null) {
                        unset($value[$i]);
                        $orNull = true;
                    }
                }

                if (count($value) > 0) {
                    if ($orNull) {
                        $queryBuilder->andWhere(
                            $queryBuilder->expr()->orX(
                                $queryBuilder->expr()->in($name, $value),
                                $orNull ? $queryBuilder->expr()->isNull($name) : null
                            )
                        );
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
                    }
                    continue;
                }

                if ($orNull) {
                    $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
                }
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $mapping = $this->_class->getFieldMapping($property);
                if ($mapping['type'] === 'string' || $mapping['type'] === 'text') {
                    $queryBuilder->andWhere($name.' LIKE :'.$parameter)->setParameter($parameter, '%'.$value.'%');
                } else {
                    $queryBuilder
                        ->andWhere($queryBuilder->expr()->eq($name, ':'.$parameter))
                        ->setParameter($parameter, $value);
                }
            }
        }
    }

}