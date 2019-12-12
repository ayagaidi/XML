<?php

namespace ACFBentveld\XML\Transformers;

use Illuminate\Support\Arr;

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
        return Arr::wrap($data);
    }
}
