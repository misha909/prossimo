<?php

namespace AppBundle\Form;

use AppBundle\Entity\Accessory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccessoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("description");
        $builder->add("quantity");
        $builder->add("extras_type");
        $builder->add("original_cost");
        $builder->add("original_currency");
        $builder->add("conversion_rate");
        $builder->add("price_markup");
        $builder->add("discount");

        //$builder->add("project");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Accessory',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'project_accessory';
    }
}