<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 8/11/16
 * Time: 22:36
 */

namespace Positibe\Bundle\CoreBundle\Form\DataTransformer;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

class EntityAjaxDataTransformer implements DataTransformerInterface
{
    protected $manager;
    protected $class;

    public function __construct(EntityManager $entityManager, $class)
    {
        $this->manager = $entityManager;
        $this->class = $class;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return null|object
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return $value;
        }
        if (is_array($value) || $value instanceof Collection) {
            return $this->manager->getRepository($this->class)->findBy(
                [$this->manager->getMetadataFactory()->getMetadataFor($this->class)->getIdentifier()[0] => $value]
            );
        } else {
            return $this->manager->getRepository($this->class)->findOneBy(
                [$this->manager->getMetadataFactory()->getMetadataFor($this->class)->getIdentifier()[0] => $value]
            );
        }
    }

}