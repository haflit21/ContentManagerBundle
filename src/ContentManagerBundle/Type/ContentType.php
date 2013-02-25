<?php

namespace NGclick\ContentManagerBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=>'Title'))
            ->add('description', 'textarea', array('label'=>'Description', 'required'=>false))            
            /*->add('description', 'ckeditor', array(
                    'transformers'           => array('strip_js', 'strip_css', 'strip_comments'),
                    'toolbar'                => array('document','basicstyles'),
                    'ui_color'               => '#fff',
                    'startup_outline_blocks' => false,
                    'width'                  => '100%',
                    'height'                 => '200',
                    'language'               => 'en-au',
                    'label'                  => 'Description',
                ))*/
            ->add('published', 'choice', array(
                'choices'=> array('1'=>'Oui', '0'=>'Non'), 
                'expanded'=>true, 
                'multiple'=>false,
                'label'=>'Published'
            ))
            /*->add('created', "date", array(
                "widget"=>"single_text",
                'input' => 'string',
                'format' => 'M/d/y', 
                'data_timezone' => "Europe/Paris",
                'user_timezone' => "Europe/Paris",
                'attr' => array('class' => 'datepicker'),
                'label'=>'Date',
                'required'=>false
            ))*/
            ->add('categories', 'entity', array(
                'class'=>'ContentManagerBundle:Category',
                'label'=>'Categories',
                'property'=>'title',
                'expanded'=>false,
                'multiple'=>true,
                'required'=>true
            ))
            ->add('metatitle', 'text', array('label'=>'MetaTitle', 'required'=>false))
            ->add('metadescription', 'text', array('label'=>'MetaDescription', 'required'=>false))
            ->add('canonical', 'text', array('label'=>'Canonical', 'required'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NGclick\ContentManagerBundle\Entity\Content'
        ));
    }

    public function getName()
    {
        return 'contentmanager_content';
    }
}
