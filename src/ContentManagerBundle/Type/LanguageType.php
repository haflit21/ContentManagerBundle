<?php

namespace NGclick\ContentManagerBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LanguageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=>'Title'))
            ->add('iso', 'text', array('label'=>'ISO Code'))
            ->add('published', 'choice', array(
                'choices'=> array('1'=>'Oui', '0'=>'Non'), 
                'expanded'=>true, 
                'multiple'=>false,
                'label'=>'Published'
            ))
            ->add('default_lan', 'choice', array(
                'choices'=> array('1'=>'Oui', '0'=>'Non'), 
                'expanded'=>true, 
                'multiple'=>false,
                'label'=>'Default'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NGclick\ContentManagerBundle\Entity\Language'
        ));
    }

    public function getName()
    {
        return 'contentmanager_language';
    }
}
