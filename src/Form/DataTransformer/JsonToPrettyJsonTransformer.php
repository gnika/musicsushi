<?php
namespace App\Form\DataTransformer;
//au final, non utilisé pour le role
use Symfony\Component\Form\DataTransformerInterface;

class JsonToPrettyJsonTransformer implements DataTransformerInterface
{
    public function transform($value)
    {die('e');
        return json_encode($value, JSON_PRETTY_PRINT);
    }

    public function reverseTransform($value)
    {
        return json_encode(json_decode($value));
    }
}