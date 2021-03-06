<?php

namespace Bacon\Bundle\LanguageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['search']) {
            $builder
                ->add('name')->setRequired(false)
                ->add('published')->setRequired(false)
            ;
        } else {
            $builder
                ->add('name')
                ->add('acron')
                ->add('locale')
                ->add('orderBy')
                ->add('image')
                ->add('published',null,[
                    'attr' => [
                        'class' => 'icheck'
                    ]
                ])
            ;
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'search'     => false,
            'data_class' => 'Bacon\Bundle\LanguageBundle\Entity\Language'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bacon_bundle_languagebundle_languageform';
    }
}
