<?php

namespace Positibe\Bundle\FilterBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\FilterBundle\Form\DataTransformer\EntityAjaxDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityAjaxFormType
 * @package AppBundle\Form
 */
class EntityAjaxFormType extends AbstractType
{
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EntityAjaxDataTransformer($this->entityManager, $options['class']));

    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace(
            $view->vars,
            array(
                'multiple' => $options['multiple'],
                'expanded' => $options['expanded'],
//            'preferred_choices' => $choiceListView->preferredChoices,
                'choices' => [],
                'separator' => '-------------------',
                'placeholder' => null,
                'class' => $options['class']
//            'choice_translation_domain' => $choiceTranslationDomain,
            )
        );

        $view->vars['attr']['data-entity'] = isset($options['attr']['data-entity']) ? $options['attr']['data-entity'] : $options['class'];
        $view->vars['attr']['data-field_text'] = $options['field_text'];
        $view->vars['attr']['data-order'] = $options['order'];

        // The decision, whether a choice is selected, is potentially done
        // thousand of times during the rendering of a template. Provide a
        // closure here that is optimized for the value of the form, to
        // avoid making the type check inside the closure.
        if ($options['multiple']) {
            $view->vars['is_selected'] = function ($choice, array $values) {
                return in_array($choice, $values, true);
            };
        } else {
            $view->vars['is_selected'] = function ($choice, $value) {
                return $choice === $value;
            };
        }

        // Only add the empty value option if this is not the case
        if (null !== $options['placeholder'] /*&& !$view->vars['placeholder_in_choices']*/) {
            $view->vars['placeholder'] = $options['placeholder'];
        }

        // BC
        $view->vars['empty_value'] = $view->vars['placeholder'];
//        $view->vars['empty_value_in_choices'] = $view->vars['placeholder_in_choices'];

        if ($options['multiple'] && !$options['expanded']) {
            // Add "[]" to the name in case a select tag with multiple options is
            // displayed. Otherwise only one of the selected options is sent in the
            // POST request.
            $view->vars['full_name'] .= '[]';
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('class', 'field_text'));
        $resolver->setDefaults(
            array(
                'order' => null,
                'multiple' => false,
                'expanded' => false,
                'choices' => array(),
//                'choices_as_values' => false,
//                'choice_loader' => null,
//                'choice_label' => $choiceLabel,
//                'choice_name' => null,
//                'choice_value' => null,
//                'choice_attr' => null,
//                'preferred_choices' => array(),
//                'group_by' => null,
//                'empty_data' => $emptyData,
                'empty_value' => new \Exception(), // deprecated
                'placeholder' => null,
                'error_bubbling' => false,
                'compound' => false,
                'data_class' => null,
                'choice_translation_domain' => true,
            )
        );

    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'entity_ajax';
    }
}
