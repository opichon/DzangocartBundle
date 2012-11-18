<?php

namespace Dzangocart\Bundle\DzangocartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccountType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('givenNames');
        $builder->add('surname');
        $builder->add('gender', 'choice', array(
            'choices'   => array('1' => 'Male', '0' => 'Female'),
            'expanded' => true,
            'required'  => false
        ));
        $builder->add('email');
        $builder->add('organization', 'text', array(
            'required'  => false
        ));
        $builder->add('vatId', 'text', array(
            'required'  => false
        ));
        $builder->add('line1');
        $builder->add('line2');
        $builder->add('city');
        $builder->add('state');
        $builder->add('zip');
        $builder->add('country');
        $builder->add('telephone');
        $builder->add('fax');
        $builder->add('mobile');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dzangocart\Bundle\DzangocartBundle\Propel\Account',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'account';
    }
}
