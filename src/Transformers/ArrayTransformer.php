<?php

namespace ACFBentveld\XML\Transformers;

class ArrayTransformer implements Transformer
{
    /**
     * Wrap the data in a array.
     *
     * @param mixed $data
     *
     * @return array|mixed
     */
    public static function apply($data)
    {
        return array_wrap($data);
    }
}
