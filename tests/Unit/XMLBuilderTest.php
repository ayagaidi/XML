<?php

namespace ACFBentveld\XML\Tests\Unit;

use ACFBentveld\XML\Tests\TestCase;
use ACFBentveld\XML\XML;

class XMLBuilderTest extends TestCase
{
    public function test_can_set_encoding()
    {
        $xml = XML::export([])
            ->encoding('iso-8859-1')
            ->toString();

        $this->assertMatchesXmlSnapshot($xml);
    }


    public function test_can_set_root_using_setter()
    {
        $xml = XML::export([])
            ->setRootTag('test')
            ->toString();

        $this->assertMatchesXmlSnapshot($xml);
    }


    public function test_can_set_root_using_dynamic()
    {
        $xml = XML::export([])
            ->rootTag('dynamic')
            ->toString();

        $this->assertMatchesXmlSnapshot($xml);
    }


    public function test_can_disable_root()
    {
        $xml = XML::export("")
            ->disableRootTag()
            ->toString();

        $xml = trim($xml); // remove forma

        $this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>", $xml);
    }


    public function test_can_set_item_name()
    {
        $xml = XML::export([ "foo", "bar", "baz" ])
            ->itemName("name")
            ->toString();

        $this->assertMatchesXmlSnapshot($xml);
    }

    public function test_generates_item_name()
    {
        $xml = XML::export([ "foo", "bar", "baz" ])
            ->rootTag("names")
            ->itemName("entry")
            ->toString();

        $this->assertMatchesXmlSnapshot($xml);
    }

    public function test_can_disable_item_name_generation()
    {
        $xml = XML::export([ "foo", "bar", "baz" ])
            ->rootTag("names")
            ->itemName("entry")
            ->forceItemName()
            ->toString();

        $this->assertMatchesXmlSnapshot($xml);
    }
}