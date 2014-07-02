<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date_from', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'dzangocart.direct_payments.filters.date_from',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date_from form-control'
            )
        ));

        $builder->add('date_to', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'dzangocart.direct_payments.filters.date_to',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date_to form-control'
            )
        ));

        $builder->add('date_range', 'text', array(
            'attr' => array(
                'class' => 'input-xlarge period form-control'
            ),
            'label' => 'dzangocart.direct_payments.filters.period'
        ));

        $builder->add('test', 'checkbox', array(
            'label' => 'dzangocart.direct_payments.filters.test',
            'attr' => array(
                'class' => 'checkbox'
            )
        ));
    }

    public function getName()
    {
        return 'filters';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'dzangocart',
        ));
    }
}
