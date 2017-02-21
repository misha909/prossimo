<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //dump($builder);
        //$label = $options['data']->getDisplayName();
        $builder->add("system_name", 'hidden');
        $builder->add("display_name",'hidden');
        //$builder->add("value", 'text', array('auto_initialize' => false,));

        $formFactory = $builder->getFormFactory();
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formFactory) {
            $form = $event->getForm();
            $data = $event->getData();

            $form->add('value', 'text', array('label' => $data->getDisplayName()));
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\AppSetting',
            'csrf_protection'   => true,
        ));
    }

    public function getName()
    {
        return 'app_setting';
    }
}
