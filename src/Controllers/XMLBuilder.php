<?php

namespace ACFBentveld\XML\Controllers;

use DOMDocument;
use DOMNode;
use Illuminate\Support\Str;

/**
 * Class XMLBuilder `version`, `rootTag`, `itemName` and `encoding`
 *
 * @method XMLBuilder version(string $version = "1.0") set the xml version
 * @method XMLBuilder encoding(string $encoding = "UTF-8") set the xml encoding
 * @method XMLBuilder rootTag(string $name = "root") set the name of the root tag
 * @method XMLBuilder itemName(string $name = "item") set the default name for items without a name
 */
class XMLBuilder
{
    /**
     * The default root name.
     */
    protected const DEFAULT_ROOT = "root";
    /**
     * @var string the encoding of the xml document.
     */
    protected $encoding = "UTF-8";
    /**
     * @var string the version of the xml document.
     */
    protected $version = "1.0";
    /**
     * @var string|boolean the name of the root tag. Set to false to disable the root tag.
     */
    protected $rootTag = self::DEFAULT_ROOT;
    /**
     * @var string|boolean the default name of xml items that where not defined with a key.
     */
    protected $itemName = "item";
    /**
     * @var array|string data for the xml
     */
    protected $data = [];


    /**
     * XMLBuilder constructor.
     *
     * @param string $encoding the encoding to use for the xml document. Defaults to "UTF-8".
     * @param string $version  the version to use for the xml document. Defaults to "1.0".
     */
    public function __construct(string $encoding = "UTF-8", string $version = "1.0")
    {
        $this->encoding = $encoding;
        $this->version = $version;
    }


    /**
     * Disable the root tag.
     *
     * @return $this
     */
    public function disableRootTag()
    {
        return $this->setRootTag(false);
    }


    /**
     * Set the root tag for the document.
     *
     * @param string|boolean $tag the name to use as the root tag. Set to `false` to disable.
     *
     * @return $this
     */
    public function setRootTag($tag)
    {
        $this->rootTag = $tag;
        return $this;
    }


    /**
     * Set the data
     *
     * @param $data
     *
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;
        return $this;
    }


    /**
     * Get the xml as a string.
     *
     * @return string
     */
    public function toString()
    {
        if (is_string($this->data)) {
            return $this->getProlog()
                . $this->openRootTag()
                . $this->data
                . $this->closeRootTag();
        }
        return $this->generate();
    }


    /**
     * Make the XML Prolog tag.
     *
     * @return string
     */
    private function getProlog()
    {
        return "<?xml version=\"{$this->version}\" encoding=\"{$this->encoding}\"?>" . PHP_EOL;
    }


    /**
     * Make the root tag. Returns `null` if the root tag is disabled.
     *
     * @return null|string
     */
    private function openRootTag()
    {
        return !$this->rootTag ? null : "<{$this->rootTag}>";
    }


    /**
     * Make the closing tag for the root tag. Returns `null` if the root tag is disabled.
     *
     * @return null|string
     */
    private function closeRootTag()
    {
        return !$this->rootTag ? null : "</{$this->rootTag}>";
    }


    /**
     * Generate xml based on a array
     *
     * @return string
     */
    private function generate()
    {
        $document = new DOMDocument($this->version, $this->encoding);
        $xmlRoot = $document->createElement($this->rootTag);
        $root = $document->appendChild($xmlRoot);
        foreach ($this->data as $field => $value) {
            if (is_array($value)) {
                $document = $this->walkArray($value, $field, $document, $root);
                continue;
            }

            $field = $this->getFieldName($field);
            $element = $document->createElement($field, $value);
            $root->appendChild($element);
        }
        return $document->saveXML();
    }


    /**
     * Walk over a array of values and add those values to the xml
     *
     * @param array        $values   - values to walk over
     * @param string       $name     - name of the parent element
     * @param \DOMDocument $document - the xml document
     * @param \DOMNode     $root     - the root element of the xml document
     *
     * @return \DOMDocument
     */
    private function walkArray(array $values, string $name, DOMDocument &$document, DOMNode $root)
    {
        foreach ($values as $value) {
            if (is_array($value)) {
                $element = $document->createElement($name);
                $parent = $root->appendChild($element);
                $this->createMultiple($name, $value, $document, $parent);
                continue;
            }
            $element = $document->createElement($name, $value);
            $root->appendChild($element);
        }
        return $document;
    }


    /**
     * Recursively create multiple xml children with the same name
     *
     * @param string       $name     - the name of the children
     * @param array        $values   - values for the children
     * @param \DOMDocument $document - the xml document
     * @param \DOMNode     $parent   - the parent element the children belong to
     */
    private function createMultiple(string $name, array $values, DOMDocument &$document, DOMNode &$parent)
    {
        foreach ($values as $field => $value) {
            if (is_array($value)) {
                $child = $parent;
                if (is_string($field)) {
                    $element = $document->createElement($field);
                    $child = $parent->appendChild($element);
                }

                $this->createMultiple($name, $value, $document, $child);
                continue;
            }
            $element = $document->createElement($field, $value);
            $parent->appendChild($element);
        }
    }


    /**
     * Generates the name for top-level tags.
     *
     * Primarily used for simple arrays that just contain values without keys.
     * If $field is a string we just return that.
     *
     * If $field is the index of generator loop we check if the root tag is the default "root",
     * in that case the name of the tag will be "item". If the root tag is a custom name we
     * get the singular form of the root name
     *
     * @param string|int $field - name or index the check
     *
     * @return string - the generated name
     */
    private function getFieldName($field)
    {
        if (!is_string($field)) {
            return $this->rootTag === "root" ? $this->itemName : Str::singular($this->rootTag);
        }
        return $field;
    }


    /**
     * Handle dynamic setters for `version`, `rootTag`, `itemName` and `encoding`
     *
     * @param $name
     * @param $arguments
     *
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, ['version', 'rootTag', 'encoding', 'itemName'])) {
            if (count($arguments) !== 1) {
                throw new \InvalidArgumentException("$name requires 1 parameter");
            }
            $this->{$name} = $arguments[0];
            return $this;
        }
        return $this;
    }
}