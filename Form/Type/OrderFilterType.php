<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderFilterType extends AbstractType
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
            'label' => 'dzangocart.orders.filters.date_from',
            'widget' => 'single_text',
//            'widget_control_group_attr' => array(
//                'class' => 'date'
//            ),
            'attr' => array(
                'class' => 'date_from form-control'
            )
        ));

        $builder->add('date_to', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'dzangocart.orders.filters.date_to',
            'widget' => 'single_text',
//            'widget_control_group_attr' => array(
//                'class' => 'date'
//            ),
            'attr' => array(
                'class' => 'date_to form-control'
            )
        ));

        $builder->add('date_range', 'text', array(
            'attr' => array(
                'class' => 'input-xlarge period form-control'
            ),
            'label' => 'dzangocart.orders.filters.period'
        ));

        $builder->add('test', 'checkbox', array(
            'label' => 'dzangocart.orders.filters.test',
            'attr' => array(
                'class' => 'checkbox'
            ),
//            'label_render' => true,
//            'widget_checkbox_label' => 'widget',
//            'widget_control_group_attr' => array(
//                'class' => 'test'
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
