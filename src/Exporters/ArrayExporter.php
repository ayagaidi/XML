<?php

namespace ACFBentveld\XML\Exporters;

use DOMNode;
use DOMDocument;
use ACFBentveld\XML\XMLBuilder;

class ArrayExporter extends XMLBuilder implements Exporter
{
    /**
     * ArrayExporter constructor.
     *
     * @param $data - data to use
     */
    public function __construct($data)
    {
        parent::__construct();

        $this->data = $data;
    }

    /**
     * Save the xml to a file.
     *
     * @param string $path - the path to the file
     */
    public function toFile(string $path)
    {
        \File::put($path, $this->toString());
    }

    /**
     * Generate xml based on a array.
     *
     * @return string
     */
    public function toString(): string
    {
        $document = new DOMDocument($this->version, $this->encoding);
        $root = $document->documentElement;

        if ($this->rootTag) {
            $xmlRoot = $document->createElement($this->rootTag);
            $root = $document->appendChild($xmlRoot);
        }

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
     * Walk over a array of values and add those values to the xml.
     *
     * @param array        $values   - values to walk over
     * @param string       $name     - name of the parent element
     * @param \DOMDocument $document - the xml document
     * @param \DOMNode     $root     - the root element of the xml document
     *
     * @return \DOMDocument
     */
    private function walkArray(array $values, string $name, DOMDocument &$document, DOMNode $root): DOMDocument
    {
        $rootElement = $document->createElement($name);

        foreach ($values as $fieldName => $value) {
            if (! is_string($fieldName)) {
                $fieldName = $this->getFieldName($name);
            }
            if (is_array($value)) {
                $element = $document->createElement($fieldName);

                if (empty($value)) {
                    $element = $document->createElement($name);
                    $parent = $root->appendChild($element);
                } elseif ($this->is_assoc($value) && ! empty($value)) {
                    if ($rootElement->parentNode === null) {
                        $element = $document->createElement($name);
                        $parent = $root->appendChild($element);
                    } else {
                        $element = $document->createElement($fieldName);
                        $parent = $rootElement->appendChild($element);
                    }
                } else {
                    $parent = $root->appendChild($element);
                }

                $this->createMultiple($fieldName, $value, $document, $parent);
                continue;
            }
            $element = $document->createElement($fieldName, $value);
            if ($this->is_assoc($values)) {
                $root->appendChild($element);
            } else {
                $rootElement->appendChild($element);
                $root->appendChild($rootElement);
            }
        }

        return $document;
    }

    /**
     * Recursively create multiple xml children with the same name.
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
                    $name = $field;
                }

                $this->createMultiple($name, $value, $document, $child);
                continue;
            }
            if (is_numeric($field)) {
                $field = $this->generateFieldName($name);
            }

            $element = $document->createElement($field, $value);
            $parent->appendChild($element);
        }
    }
}
