<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name");
        $builder->add("type");
        $builder->add("url");
        //$builder->add("project");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\File',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'project_file';
    }
}
