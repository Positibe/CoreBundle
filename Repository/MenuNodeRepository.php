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

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\TranslatableListener;
use Positibe\Bundle\OrmContentBundle\Entity\MenuNode;
use Positibe\Bundle\OrmMenuBundle\Entity\MenuNodeRepositoryInterface;
use Positibe\Bundle\OrmMenuBundle\Entity\MenuNodeRepositoryTrait;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * Class MenuNodeRepository
 * @package Positibe\Bundle\CmfBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeRepository extends EntityRepository implements MenuNodeRepositoryInterface
{
    use MenuNodeRepositoryTrait;

    private $locale;

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getQuery(QueryBuilder $qb)
    {
        $query = $qb->getQuery();
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Positibe\\Bundle\\CmfBundle\\Doctrine\\Query\\TranslationWalker'
        );
        if ($this->locale) {
            $query->setHint(
                TranslatableListener::HINT_TRANSLATABLE_LOCALE,
                $this->locale // take locale from session or request etc.
            );

            $query->setHint(
                TranslatableListener::HINT_FALLBACK,
                1 // fallback to default values in case if record is not translated
            );
        }

        return $query;
    }


    public function createNewByParentName($name)
    {
        $parent = $this->findOneByName($name);

        $menu = $this->createNew();
        $menu->setParent($parent);

        return $menu;
    }

    public function createNewChildByParentName($parent, $name)
    {
        $parent = $this->getQueryBuilder()
            ->join($this->getAlias() . '.parent', 'parent')
            ->where($this->getAlias() . '.name = :name AND parent.name = :parent')
            ->setParameter('name', $name)
            ->setParameter('parent', $parent)
            ->getQuery()->getOneOrNullResult();

        $menu = $this->createNew();
        $menu->setParent($parent);

        return $menu;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (isset($criteria['parent'])) {
            $queryBuilder
                ->join($this->getAlias() . '.parent', 'p')
                ->andWhere('p.name = :parent_name')
                ->setParameter(
                    'parent_name',
                    $criteria['parent']
                );
            unset($criteria['parent']);
        }

        foreach ($criteria as $property => $value) {
            $name = $this->getPropertyName($property);
            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                    ->setParameter($parameter, $value);
            }
        }
    }

    public function createNewByParentId($id)
    {
        $parent = $this->find($id);

        $menu = new MenuNode();
        $menu->setParent($parent);

        return $menu;
    }
} 