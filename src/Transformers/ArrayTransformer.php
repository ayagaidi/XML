<?php

namespace ACFBentveld\XML\Transformers;

class ArrayTransformer implements Transformer
{
    public static function apply($data)
    {
        return array_wrap($data);
    }
}