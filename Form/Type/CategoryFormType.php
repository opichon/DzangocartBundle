<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($builder->getData()) {
            $builder->setData(
                array(
                    'taxIncluded' => (bool)$builder->getData()['taxIncluded'],
                    'export'      => (bool)$builder->getData()['export'] ,
                    'shipping'    => (bool)$builder->getData()['shipping'],
                    'download'    => (bool)$builder->getData()['download'],
                    'pack'        => (bool)$builder->getData()['pack']
                )
            );
        }

        $builder->add('name', 'text', array(
            'label' => 'dzangocart.catalogue.name',
        ));

        $builder->add('code', 'text', array(
            'label' => 'dzangocart.catalogue.code'
        ));

        $builder->add('suffix', 'text', array(
            'label' => 'dzangocart.catalogue.suffix'
        ));

        $builder->add('price', 'text', array(
            'label' => 'dzangocart.catalogue.price'
        ));
        
        $builder->add('taxIncluded', 'checkbox', array(
            'label' => 'dzangocart.catalogue.tax_included'
        ));

        $builder->add('export', 'checkbox', array(
            'label' => 'dzangocart.catalogue.export'
        ));

        $builder->add('shipping', 'checkbox', array(
            'label' => 'dzangocart.catalogue.shipping'
        ));

        $builder->add('download', 'checkbox', array(
            'label' => 'dzangocart.catalogue.download'
        ));

        $builder->add('pack', 'checkbox', array(
            'label' => 'dzangocart.catalogue.pack'
        ));

        $builder->add('minQuantity', 'text', array(
            'label' => 'dzangocart.catalogue.min_quantity'
        ));

        $builder->add('maxQuantity', 'text', array(
            'label' => 'dzangocart.catalogue.max_quantity'
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
