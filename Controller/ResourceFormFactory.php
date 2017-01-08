<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CoreBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 *
 * @deprecated To be remove in ResourceBundle 1.0.0 official release
 * @fixme Eliminar cuando salga el release oficial de ResourceBundle
 */
final class ResourceFormFactory implements ResourceFormFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(RequestConfiguration $requestConfiguration, ResourceInterface $resource)
    {
        $formType = $requestConfiguration->getFormType();
        $formOptions = $requestConfiguration->getFormOptions();

        if ($requestConfiguration->isHtmlRequest()) {
            return $this->formFactory->create($formType, $resource, $formOptions);
        }

        return $this->formFactory->createNamed(
            '',
            $formType,
            $resource,
            array_merge($formOptions, ['csrf_protection' => false])
        );
    }
}
