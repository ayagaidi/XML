<?php

namespace ACFBentveld\XML\Tests\Features\Import;

use ACFBentveld\XML\XML;
use ACFBentveld\XML\Tests\TestCase;

/**
 * This test is based on version 1.* of the package. This will change in v2.
 */
class SimpleImportTest extends TestCase
{
    public function test_import()
    {
        $path = __DIR__.'/stubs/simple.xml';
        $xml = XML::path($path)->raw();
        $this->assertMatchesJsonSnapshot(json_encode($xml));
    }

    public function test_import_optimized()
    {
        $path = __DIR__.'/stubs/simple.xml';
        $xml = XML::path($path)->optimize()->object();
        $this->assertMatchesJsonSnapshot(json_encode($xml));

        $xml = XML::path($path)->optimize()->collect();
        $this->assertMatchesJsonSnapshot(json_encode($xml));
    }

    public function test_loads_xml()
    {
        $path = __DIR__.'/stubs/notes.xml';
        $xml = XML::import($path);

        $this->assertMatchesJsonSnapshot($xml->toJson());
    }
}
