<?php

namespace ContentManagerBundle\ContentManagerBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=>'Title'))
            ->add('description', 'textarea', array(
                'label'     => 'Description',
                'attr'      => array('class'=>'ckeditor'),
                'required'   => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ContentManagerBundle\ContentManagerBundle\Entity\CMCategory'
        ));
    }

    public function getName()
    {
        return 'contentmanager_category';
    }
}
