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
            'label' => 'category.form.name',
            'required' => false
        ));

        $builder->add('code', 'text', array(
            'label' => 'category.form.code',
            'required' => false
        ));

        $builder->add('suffix', 'text', array(
            'label' => 'category.form.suffix',
            'required' => false
        ));

        $builder->add('price', 'text', array(
            'label' => 'category.form.price',
            'required' => false
        ));

        $builder->add('taxIncluded', 'checkbox', array(
            'label' => 'category.form.tax_included',
            'required' => false
        ));

        $builder->add('export', 'checkbox', array(
            'label' => 'category.form.export',
            'required' => false
        ));

        $builder->add('shipping', 'checkbox', array(
            'label' => 'category.form.shipping',
            'required' => false
        ));

        $builder->add('download', 'checkbox', array(
            'label' => 'category.form.download',
            'required' => false
        ));

        $builder->add('pack', 'checkbox', array(
            'label' => 'category.form.pack',
            'required' => false
        ));

        $builder->add('minQuantity', 'text', array(
            'label' => 'category.form.min_quantity',
            'required' => false
        ));

        $builder->add('maxQuantity', 'text', array(
            'label' => 'category.form.max_quantity',
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
