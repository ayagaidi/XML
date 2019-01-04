<?php

namespace ACFBentveld\XML\Data;

class XMLElement extends \SimpleXMLElement
{
    /**
     * Get a attribute by name.
     *
     * @param string     $name    - name of the attribute to get
     * @param null|mixed $default - default value if the attribute does not exist
     *
     * @return mixed|null
     */
    public function attribute(string $name, $default = null)
    {
        return (string) $this->attributes()->{$name} ?: $default;
    }
}
