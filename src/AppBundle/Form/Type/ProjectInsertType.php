<?php

namespace  AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ProjectInsertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('save', SubmitType::class, array('label' => 'Ajouter'))
        ;
    }

    public function getParent()
    {
        return ProjectType::class;
    }
}