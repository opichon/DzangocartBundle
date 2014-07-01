<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SipsFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date_from', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'dzangocart.sips.filters.date_from',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date_from'
            )
        ));

        $builder->add('date_to', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'dzangocart.sips.filters.date_to',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date_to'
            )
        ));

        $builder->add('date_range', 'text', array(
            'attr' => array(
                'class' => 'period'
            ),
            'label' => 'dzangocart.sips.filters.period'
        ));

        $builder->add('test', 'checkbox', array(
            'label' => 'dzangocart.sips.filters.test',
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
