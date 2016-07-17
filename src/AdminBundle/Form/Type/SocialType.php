<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocialType  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fb', 'text', array('required' => false))
            ->add('tw', 'text', array('required' => false))
            ->add('gl', 'text', array('required' => false))
            ->add('pn', 'text', array('required' => false))
            ->add('in', 'text', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\\Document\\Social'
        ));
    }

    public function getName()
    {
        return 'social';
    }
}

