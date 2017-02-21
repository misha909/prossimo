<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppSettingsCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('app_settings', 'collection', array('type' => new AppSettingType(), 'options' => array('label' => false)))
                ->add('save', 'submit', array('label' => 'Save settings', 'attr' => array('class' => 'btn btn-default')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Model\AppSettingsCollection',
            'csrf_protection'   => true,
        ));
    }

    public function getName()
    {
        return 'app_settings_collection';
    }
}
