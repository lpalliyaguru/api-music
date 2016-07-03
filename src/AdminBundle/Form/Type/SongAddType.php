<?php

namespace AdminBundle\Form\Type;


use AppBundle\Document\Artist;
use AppBundle\Document\Meta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SongAddType extends AbstractType
{
    private $cache = array();

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'displayName',
                'text'
            )
            ->add(
                'description',
                'textarea'
            )
            ->add(
                'release',
                'text'
            )
            ->add(
                'isrc',
                'text'
            )
            ->add(
                'publisher',
                'text'
            )
            ->add(
                'composer',
                'text'
            )
            ->add(
                'tags',
                'choice',
                array(
                    'choices'   => array(),
                    'multiple'  => true,
                    'attr'      => array(
                        'required' => false
                    )
                )
            )
            ->add(
                'buyLink',
                'text'
            )
            ->add(
                'genre',
                'choice',
                array(
                    'choices' => Meta::$genres,
                    'multiple' => true
                )
            )
            /*->add(
                'imageFile',
                'file'
            )
            ->add(
                'bannerFile',
                'file'
            )*/
            ->add(
                'published',
                'checkbox'
            )
            ->addEventListener(FormEvents::PRE_SUBMIT,  array($this, 'preSubmitData'))
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'postSetData'))
        ;
        $builder->get('tags')->resetViewTransformers();
    }

    public function preSubmitData(FormEvent $event)
    {
        $song = $event->getData();
        $this->cache['published']   = !isset($song['published']) ? false : true;
        $this->cache['tags']        = !isset($song['tags']) ? array() : $song['tags'];
    }

    public function postSetData(FormEvent $event)
    {
        $song = $event->getData();
        $song->setPublished($this->cache['published']);
        $song->setTags($this->cache['tags']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => 'AppBundle\\Document\\Song',
                'csrf_protection'    => true,
                'allow_extra_fields' => true,
                'error_bubbling' => true
            )
        );
    }

    public function getName()
    {
        return 'song';
    }
}