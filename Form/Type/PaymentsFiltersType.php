<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentsFiltersType extends AbstractType
{
    protected $services;

    public function __construct($services)
    {
        $this->services = $services;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('order_id', 'text', array(
            'required' => false
        ));

        $builder->add('service_id', 'choice', array(
            'choices'   => $this->getGatewayServices(),
            'required' => false
        ));

        $builder->add('status', 'choice', array(
            'choices'   => array(
                0 => 'payments.status.label.open',
                2 => 'payments.status.label.cancelled',
                4 => 'payments.status.label.error',
                8 => 'payments.status.label.approved',
                16 => 'payments.status.label.paid'
            ),
            'required' => false
        ));

        $builder->add('date_from', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'payments.filters.date_from',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date_from'
            )
        ));

        $builder->add('date_to', 'date', array(
            'format' => 'yyyy-MM-dd',
            'label' => 'payments.filters.date_to',
            'widget' => 'single_text',
            'attr' => array(
                'class' => 'date_to'
            )
        ));

        $builder->add('period', 'text', array(
            'attr' => array(
                'class' => 'period'
            ),
            'label' => 'payments.filters.period'
        ));

        $builder->add('test', 'checkbox', array(
            'label' => 'payments.filters.test_payments.label',
            'attr' => array(
                'class' => 'checkbox'
            )
        ));
    }

    public function getName()
    {
        return 'filters';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'dzangocart'
        ));
    }

    protected function getGatewayServices()
    {
        $services = array();

        foreach ($this->services as $service) {
            $services[$service['id']] = $service['value'];
        }

        return $services;
    }

}
