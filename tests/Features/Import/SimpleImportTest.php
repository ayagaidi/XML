<?php

namespace ACFBentveld\XML\Tests\Features\Import;

use ACFBentveld\XML\XML;
use ACFBentveld\XML\Tests\TestCase;

/**
 * This test is based on version 1.* of the package. This will change in v2.
 */
class SimpleImportTest extends TestCase
{
    public function test_loads_xml()
    {
        $path = __DIR__.'/stubs/notes.xml';
        $xml = XML::import($path);

        $this->assertMatchesJsonSnapshot($xml->toJson());

        $path = __DIR__.'/stubs/notes-2.xml';
        $xml = XML::import($path);

        $this->assertMatchesJsonSnapshot($xml->toJson());

        $path = __DIR__.'/stubs/plants.xml';
        $xml = XML::import($path);

        $this->assertMatchesJsonSnapshot($xml->toJson());
    }

    public function test_optimize()
    {
        $path = __DIR__.'/stubs/notes.xml';
        $xml = XML::import($path)
            ->optimize();

        $this->assertMatchesJsonSnapshot($xml->toJson());

        $path = __DIR__.'/stubs/notes-2.xml';
        $xml = XML::import($path)
            ->optimize();

        $this->assertMatchesJsonSnapshot($xml->toJson());
    }

    public function test_optimize_camel_case()
    {
        $path = __DIR__.'/stubs/notes.xml';
        $xml = XML::import($path)
            ->optimize('camelcase');

        $this->assertMatchesJsonSnapshot($xml->toJson());

        $path = __DIR__.'/stubs/notes-2.xml';
        $xml = XML::import($path)
            ->optimize('camelcase');

        $this->assertMatchesJsonSnapshot($xml->toJson());
    }
}
