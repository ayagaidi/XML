<?php


namespace ACFBentveld\XML\Data;


class XMLElement extends \SimpleXMLElement
{
    public function attribute(string $name, $default = null)
    {
        return (string)$this->attributes()->{$name} ?: $default;
    }
}