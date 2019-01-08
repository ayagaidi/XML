<?php

namespace ACFBentveld\XML;

use Illuminate\Support\Facades\Facade;

class XMLFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return XML::class;
    }
}
