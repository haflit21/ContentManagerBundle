<?php

namespace NGclick\ContentManagerBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=>'Title'))
            ->add('description', 'ckeditor', array(
                'transformers'           => array('strip_js', 'strip_css', 'strip_comments'),
                'toolbar'                => array('document','basicstyles'),
                'ui_color'               => '#fff',
                'startup_outline_blocks' => false,
                'width'                  => '100%',
                'height'                 => '200',
                'language'               => 'en-au',
                'label'                  => 'Description',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NGclick\ContentManagerBundle\Entity\Category'
        ));
    }

    public function getName()
    {
        return 'contentmanager_category';
    }
}
