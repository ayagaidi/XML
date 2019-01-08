<?php


namespace ACFBentveld\XML\Tests\Unit;


use ACFBentveld\XML\Tests\TestCase;
use ACFBentveld\XML\XML;

class XMLImportTest extends TestCase
{
    public function test_can_import_xml()
    {
        $path = __DIR__ . '/../Features/Import/stubs/notes.xml';
        $xml = XML::import($path);

        $this->assertMatchesJsonSnapshot($xml->toJson());
    }


    public function test_can_get_root_attributes()
    {
        $path = __DIR__ . '/../Features/Import/stubs/notes.xml';
        $xml = XML::import($path)->get();

        $this->assertEquals("1", $xml->attribute('count'));
        $this->assertFalse($xml->hasAttribute('foobar'));
        $this->assertEquals("default value", $xml->attribute('baz', 'default value'));
    }


    public function test_can_get_element_attributes()
    {
        $path = __DIR__ . '/../Features/Import/stubs/notes.xml';
        $xml = XML::import($path)->get()->note;

        $this->assertEquals("true", $xml->attribute('completed'));
        $this->assertFalse($xml->hasAttribute('foobar'));
        $this->assertEquals("default value", $xml->attribute('baz', 'default value'));
    }
}