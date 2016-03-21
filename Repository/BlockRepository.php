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

use Positibe\Bundle\OrmBlockBundle\Entity\BlockRepositoryInterface;
use Positibe\Bundle\OrmContentBundle\Entity\Traits\BlockRepositoryTrait;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * Class BlockRepository
 * @package Positibe\Bundle\CmfBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class BlockRepository extends EntityRepository implements BlockRepositoryInterface
{
    use BlockRepositoryTrait;
} 