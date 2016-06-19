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

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider as SyliusSingleResourceProvider;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * Class SingleResourceProvider
 * @package Positibe\Bundle\CmfBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class SingleResourceProvider extends SyliusSingleResourceProvider
{
    /**
     * {@inheritdoc}
     *
     * @deprecated
     * @fixme  in the next version of Sylius 0.18.0 or higher
     */
    public function get(RequestConfiguration $requestConfiguration, RepositoryInterface $repository)
    {
        if (null !== $repositoryMethod = $requestConfiguration->getRepositoryMethod()) {
            $callable = [$repository, $repositoryMethod];

            return call_user_func_array($callable, $requestConfiguration->getRepositoryArguments());
        }

        $criteria = [];
        $request = $requestConfiguration->getRequest();

        if ($request->attributes->has('id')) {
            return $repository->find($request->attributes->get('id'));
        }

        if ($request->attributes->has('slug')) {
            $criteria = ['slug' => $request->attributes->get('slug')];
        }
        $criteria = array_merge($criteria, $requestConfiguration->getCriteria());

        return $repository->findOneBy($criteria);
    }
} 