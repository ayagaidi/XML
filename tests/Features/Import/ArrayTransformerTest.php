<?php

namespace ACFBentveld\XML\Tests\Features\Import;

use ACFBentveld\XML\XML;
use ACFBentveld\XML\Tests\TestCase;
use ACFBentveld\XML\Transformers\ArrayTransformer;

class ArrayTransformerTest extends TestCase
{
    public function test_applies_array_transform()
    {
        $path = __DIR__.'/stubs/notes.xml';
        $xml = XML::import($path)
            ->transform('note')->with(ArrayTransformer::class)
            ->get();

        //dd($xml);

        $this->assertMatchesJsonSnapshot($xml->toJson());
    }
}
