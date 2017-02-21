<?php

namespace AppBundle\Form;

use AppBundle\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("mark");
        $builder->add("width");
        $builder->add("height");
        $builder->add("quantity");
        $builder->add("type");
        $builder->add("description");
        $builder->add("notes");
        $builder->add("profile_name");
        $builder->add("customer_image");
        $builder->add("internal_color");
        $builder->add("external_color");
        $builder->add("interior_handle");
        $builder->add("exterior_handle");
        $builder->add("hardware_type");
        $builder->add("lock_mechanism");
        $builder->add("glazing_bead");
        $builder->add("gasket_color");
        $builder->add("hinge_style");
        $builder->add("opening_direction");
        $builder->add("internal_sill");
        $builder->add("external_sill");
        $builder->add("glazing");
        $builder->add("uw");
        $builder->add("original_cost");
        $builder->add("original_currency");
        $builder->add("conversion_rate");
        $builder->add("price_markup");
        $builder->add("discount");
        $builder->add("root_section");
        $builder->add("glazing_bar_width");
        //$builder->add("profile");
        //$builder->add("project");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Unit',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'project_unit';
    }
}
