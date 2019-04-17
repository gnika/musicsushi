<?php

namespace App\Form;

use App\Entity\Music;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class MusicType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//        $pays = $options['entityManager']->getRepository('DfactBundle:Pays')->findBy([], array('paysFr' => 'ASC'));
//        $result = [];
//
//        foreach($pays as $pp)
//            $result[$pp->getPaysFr()] = $pp->getPaysFr();
        $builder->add('title', TextType::class, [
            'label' => 'titre de la musique',
            'required' => false,
            'attr' => array(
                'class' => 'form-control border-input'
            ),
        ])
        ->add('file', FileType::class, [
            'label' => 'Fichier mp3',
            'attr' => array(
                'class' => 'form-control border-input'
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
            'data_class' => Music::class,
            'entityManager' => null
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
