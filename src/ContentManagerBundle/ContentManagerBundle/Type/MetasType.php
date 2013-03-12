<?php

namespace ContentManagerBundle\ContentManagerBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use ContentManagerBundle\ContentManagerBundle\Entity\Repository\CategoryRepository;

class MetasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('metatitle', 'text', array('label'=>'MetaTitle', 'required'=>false))
            ->add('metadescription', 'text', array('label'=>'MetaDescription', 'required'=>false))
            ->add('canonical', 'text', array('label'=>'Canonical', 'required'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ContentManagerBundle\ContentManagerBundle\Entity\CMMetas'
        ));
    }

    public function getName()
    {
        return 'contentmanager_metas';
    }
}
