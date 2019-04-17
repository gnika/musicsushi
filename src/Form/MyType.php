<?php

namespace App\Form;

use App\Entity\Music;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class MyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

       $emailUser = $options['emailUser'];
        $this->emailUser = $emailUser;
       $builder->add('users_friendasks', EntityType::class, [
            'label' => 'Liste des utilisateurs',
            'required' => true,
            'multiple' => true,
            'class' => Users::class,
            'choice_label'=> 'email',
            'query_builder' => function(EntityRepository $er) {
                return $er->otherEmail($this->emailUser);
            },
            'attr' => ['autocomplete' => "Choisir", 'class' => "hidden"]
        ])


            ->add('Enregistrer', SubmitType::class, [
            'attr' => ['class' => ' custom_button'],
        ])
        ;
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Users::class,
            'emailUser' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'users';
    }


}
