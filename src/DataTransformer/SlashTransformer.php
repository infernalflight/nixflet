<?php

namespace App\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class SlashTransformer implements DataTransformerInterface
{

    public function transform(mixed $value): mixed
    {
        if (!$value) {
            return [];
        }

        return explode(' / ', $value);
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (!$value) {
            return null;
        }

        return implode(' / ', $value);
    }
}
