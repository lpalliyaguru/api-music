<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                'email',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Email()
                    )
                )
            )
            ->add(
                'username',
                'text',
                array(
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->add(
                'name',
                'text',
                array(
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->add(
                'plainPassword',
                'text',
                array(
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'postSetData'))
        ;
    }

    public function postSetData()
    {

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => 'AppBundle\\Document\\User',
                'csrf_protection'    => false,
                'allow_extra_fields' => true,
            )
        );
    }
    public function getName()
    {
        return '';
    }
}