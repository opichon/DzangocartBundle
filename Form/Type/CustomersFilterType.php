<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomersFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('gender', 'choice', array(
            'choices' => array(1=>'customers.filters.gender.male', 0=>'customers.filters.gender.female'),
            'required' => false
        ));

        $builder->add('email', 'text', array(
            'required' => false
        ));

        $builder->add('customer', 'text', array());

        $builder->add('customer_id', 'hidden', array());

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
