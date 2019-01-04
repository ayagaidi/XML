<?php

namespace ACFBentveld\XML\Data;

use Illuminate\Support\Collection;

class XMLCollection
{

    private $items;


    public function __construct($items)
    {
        $this->items = new Collection($items);
    }


    public function __call(string $name, array $arguments)
    {
        return $this->items->{$name}(...$arguments);
    }


    public function __get($key)
    {
        var_dump($key);
    }
}