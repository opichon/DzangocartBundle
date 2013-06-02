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
            'attr' => array(
                'class' => 'date',
                'placeholder' => strtolower($this->getDateFormat()),
            ),
            'format' => $this->getDateFormat(),
            'label' => 'dzangocart.orders.filters.date_from',
            'widget' => 'single_text',
            'widget_control_group_attr' => array(
                'class' => 'date'
            )
        ));
        $builder->add('date_to', 'date', array(
            'attr' => array(
                'class' => 'date',
                'placeholder' => strtolower($this->getDateFormat()),
            ),
            'format' => $this->getDateFormat(),
            'label' => 'dzangocart.orders.filters.date_to',
            'widget' => 'single_text',
            'widget_control_group_attr' => array(
                'class' => 'date'
            )
        ));

        $builder->add('include_test_orders', 'checkbox', array(
            'label' => 'dzangocart.orders.filters.test',
            'label_render' => true,
            'widget_checkbox_label' => 'widget',
            'widget_control_group_attr' => array(
                'class' => 'test'
            ),
            'widget_type' => 'inline'
        ));
    }

    public function getName()
    {
        return 'filters';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'name'       => 'filters',
            'translation_domain' => 'dzangocart',
//            'show_legend' => false,
//            'checkbox_label' => 'widget'
        ));
    }
}