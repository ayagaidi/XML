<?php

namespace ACFBentveld\XML\Casts;

use Illuminate\Database\Eloquent\Model;

class Cast
{
    /**
     * Cast a array to the given class.
     *
     * @param array $what - values to pass to the cast
     * @param       $to   - class to cast
     *
     * @return mixed
     */
    public static function to(array $what, $to)
    {
        $interfaces = class_implements($to);
        if ($to instanceof Model) {
            return new $to($what);
        } elseif ($to instanceof Castable || isset($interfaces[Castable::class])) {
            return $to::fromCast($what);
        }

        return new $to($what);
    }
}
