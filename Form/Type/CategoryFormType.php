<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => 'dzangocart.catalogue.name',
            'attr' => array(
                'class' => 'form-control'
            )
        ));

        $builder->add('code', 'text', array(
            'label' => 'dzangocart.catalogue.code',
            'attr' => array(
                'class' => 'form-control'
            )
        ));

        $builder->add('pcode', 'text', array(
            'label' => 'dzangocart.catalogue.pcode',
            'attr' => array(
                'class' => 'form-control'
            )
        ));

        $builder->add('price', 'text', array(
            'label' => 'dzangocart.catalogue.price',
            'attr' => array(
                'class' => 'form-control'
            )
        ));

        $builder->add('tax_included', 'text', array(
            'label' => 'dzangocart.catalogue.tax_included',
            'attr' => array(
                'class' => 'form-control'
            )
        ));
    }

    public function getName()
    {
        return 'category';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'dzangocart',
        ));
    }
}
