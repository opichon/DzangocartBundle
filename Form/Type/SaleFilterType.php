<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaleFilterType extends AbstractType
{
    protected $options = array();

    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    public function getDateFormat()
    {
        return @$this->options['date_format'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date_from', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'dzangocart.sales.filters.date_from',
            'widget' => 'single_text',
//            'widget_control_group_attr' => array(
//                'class' => 'date'
//            ),
            'attr' => array(
                'class' => 'date_from'
            )
        ));
        $builder->add('date_to', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'dzangocart.sales.filters.date_to',
            'widget' => 'single_text',
//            'widget_control_group_attr' => array(
//                'class' => 'date'
//            ),
            'attr' => array(
                'class' => 'date_to'
            )
        ));

        $builder->add('date_range', 'text', array(
            'attr' => array(
                'class' => 'input-xlarge period'
            ),
            'label' => 'dzangocart.sales.filters.period'
        ));

        $builder->add('test', 'checkbox', array(
            'label' => 'dzangocart.sales.filters.test',
//            'widget_checkbox_label' => 'widget',
//            'widget_control_group_attr' => array(
 //               'class' => 'test'
//            ),
//            'widget_type' => 'inline'
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
