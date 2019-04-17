<?php

namespace App\Form;

use App\Entity\CommentMusic;
use App\Entity\Music;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CommentMusicType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $music = $options['music'];
        $currentUser = $options['users'];
        $builder->add('comment', TextareaType::class, [
            'label' => 'Commentaire',
            'required' => false,
            'attr' => array(
                'class' => 'form-control border-input'
            ),
        ])
        ->add('timeMusic', TextType::class, [
            'label' => 'temps musique',
            'required' => false,
            'attr' => array(
                'class' => 'hidden'
            )
        ])
        ->add('users', EntityType::class, [
            'class' => Users::class,
            'choice_label' => 'email',
            'data'         => $currentUser,
            'attr' => array(
                'class' => 'hidden'
            )
         ])
        ->add('music', EntityType::class, [
            'class' => Music::class,
            'choice_label' => 'title',
            'data'         => $music,
            'attr' => array(
               'class' => 'hidden'
             )
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
            'data_class' => CommentMusic::class,
            'music' => null,
            'users' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'music';
    }


}
