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
            'required' => false
        ));

        $builder->add('code', 'text', array(
            'label' => 'dzangocart.catalogue.code',
            'required' => false
        ));

        $builder->add('suffix', 'text', array(
            'label' => 'dzangocart.catalogue.suffix',
            'required' => false
        ));

        $builder->add('price', 'text', array(
            'label' => 'dzangocart.catalogue.price',
            'required' => false
        ));

        $builder->add('taxIncluded', 'checkbox', array(
            'label' => 'dzangocart.catalogue.tax_included',
            'required' => false
        ));

        $builder->add('export', 'checkbox', array(
            'label' => 'dzangocart.catalogue.export',
            'required' => false
        ));

        $builder->add('shipping', 'checkbox', array(
            'label' => 'dzangocart.catalogue.shipping',
            'required' => false
        ));

        $builder->add('download', 'checkbox', array(
            'label' => 'dzangocart.catalogue.download',
            'required' => false
        ));

        $builder->add('pack', 'checkbox', array(
            'label' => 'dzangocart.catalogue.pack',
            'required' => false
        ));

        $builder->add('minQuantity', 'text', array(
            'label' => 'dzangocart.catalogue.min_quantity',
            'required' => false
        ));

        $builder->add('maxQuantity', 'text', array(
            'label' => 'dzangocart.catalogue.max_quantity',
            'required' => false
        ));

        $builder->add('Save', 'submit');
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
