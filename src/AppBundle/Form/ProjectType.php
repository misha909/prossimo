<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("pipedrive_id");
        $builder->add("client_name");
        $builder->add("client_company_name");
        $builder->add("client_phone");
        $builder->add("client_email", "email");
        $builder->add("client_address");
        $builder->add("project_name");
        $builder->add("project_address");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Project',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'project';
    }
}
