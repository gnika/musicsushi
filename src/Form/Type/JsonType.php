<?php
namespace App\Form\Type;

use App\Form\DataTransformer\JsonToPrettyJsonTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class JsonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
//        $builder->setDataLocked('ROLE_USER');

        $builder->addViewTransformer(new JsonToPrettyJsonTransformer());
    }

    public function getParent()
    {
//        return ChoiceType::class;
        return TextareaType::class;//changer la config easy_admin
    }
}