<?php

namespace AdminBundle\Form\Type;


use AppBundle\Document\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AlbumCreateType extends AbstractType
{
    private $cache = array();

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text'
            )
            ->add(
                'albumId',
                'text',
                array(
                    'required' => false
                )
            )
            ->add(
                'about',
                'textarea'
            )
            ->add(
                'release',
                'text'
            )
            /*->add(
                'genre',
                'choice',
                array(
                    'choices' =>  array(
                        'Yes' => true,
                        'No' => false,
                        'Maybe' => null
                    )
                )
            )*/
            ->add(
                'imageFile',
                'file'
            )
            ->add(
                'bannerFile',
                'file'
            )
            ->add(
                'active',
                'checkbox'
            )
            ->addEventListener(FormEvents::PRE_SUBMIT,  array($this, 'preSubmitData'))
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'postSetData'))
        ;
    }

    public function preSubmitData(FormEvent $event)
    {
        $album = $event->getData();
        //Set the artist inactive if the chack box is not ticked
        $this->cache['active'] = !isset($album['active']) ? false : true;

    }

    public function postSetData(FormEvent $event)
    {
        $album = $event->getData();
        $album->setActive($this->cache['active']);
        /*
        $artist->setArtistId($this->cache['artistId']);*/
        //$property->getLocation()->cleanCoords(); //cleaning the coordinates
        //$property->getAsset()->setImages($this->cache['images']); //setting missing images. This, we will have to use for other arrays as well
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'         => 'AppBundle\\Document\\Album',
                'csrf_protection'    => true,
                'allow_extra_fields' => true,
                'error_bubbling' => true
            )
        );
    }

    public function getName()
    {
        return 'album';
    }
}