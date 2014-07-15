<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrdersFilterType extends AbstractType
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
        $builder->add('order_id', 'text', array());

        $builder->add('name', 'text', array());

        $builder->add('customer', 'text', array());

        $builder->add('customer_id', 'hidden', array());

        $builder->add('date_from', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'orders.filters.date_from',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date date_from'
            )
        ));

        $builder->add('date_to', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'orders.filters.date_to',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date date_to'
            )
        ));

        $builder->add('period', 'text', array(
            'attr' => array(
                'class' => 'period form-control'
            ),
            'label' => 'orders.filters.period'
        ));

        $builder->add('test', 'checkbox', array(
            'label' => 'orders.filters.test_orders.label',
            'attr' => array(
                'class' => 'checkbox'
            ),
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
