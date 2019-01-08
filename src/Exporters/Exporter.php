<?php

namespace ACFBentveld\XML\Exporters;

interface Exporter
{
    public function toString(): string;
}
