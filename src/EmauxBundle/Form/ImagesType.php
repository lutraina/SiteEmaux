<?php

namespace EmauxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'file', array(
                'data_class' => null,
                'required' => false
            ))
            /*->add('categorie', 'entity', array(
                'class' => 'EmauxBundle:Categorie',
                                    'property' => 'nom',
                                    'empty_value' => '-- sélectionner une catégorie --',
                                    'label'    => 'Choisir la catégorie : '))*/
            ->add('categorie', 'entity', array(
            	'class' => 'EmauxBundle:Categorie', 
            	'empty_value' => '-- Sélectionnez une catégorie --'
            	))
            ->add('description')
        ;
    }
    
    
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EmauxBundle\Entity\Images'
        ));
    }
    
    
    
}
