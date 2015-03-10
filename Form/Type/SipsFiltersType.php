<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SipsFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date_from', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'sips.filters.date_from',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date date_from',
            ),
        ));

        $builder->add('date_to', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'sips.filters.date_to',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date date_to',
            ),
        ));

        $builder->add('period', 'text', array(
            'attr' => array(
                'class' => 'period',
            ),
            'label' => 'sips.filters.period',
        ));

        $builder->add('test', 'checkbox', array(
            'label' => 'sips.filters.test_transactions.label',
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
