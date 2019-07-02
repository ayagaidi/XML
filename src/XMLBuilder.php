<?php

namespace ACFBentveld\XML;

use Illuminate\Support\Str;

/**
 * Class XMLBuilder `version`, `rootTag`, `itemName` and `encoding`.
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
    protected const DEFAULT_ROOT = 'root';
    /**
     * @var string the encoding of the xml document.
     */
    protected $encoding = 'UTF-8';
    /**
     * @var string the version of the xml document.
     */
    protected $version = '1.0';
    /**
     * @var string|bool the name of the root tag. Set to false to disable the root tag.
     */
    protected $rootTag = self::DEFAULT_ROOT;
    /**
     * @var string|bool the default name of xml items that where not defined with a key.
     */
    protected $itemName = 'item';
    /**
     * @var array|string data for the xml
     */
    protected $data = [];
    /**
     * @var bool force usage of the item name instead a name generated based on the root tag
     */
    protected $forceItemName = false;

    /**
     * XMLBuilder constructor.
     *
     * @param string $encoding the encoding to use for the xml document. Defaults to "UTF-8".
     * @param string $version  the version to use for the xml document. Defaults to "1.0".
     */
    public function __construct(string $encoding = 'UTF-8', string $version = '1.0')
    {
        $this->encoding = $encoding;
        $this->version = $version;
    }

    /**
     * Disable the root tag.
     *
     * @return $this
     */
    public function disableRootTag(): XMLBuilder
    {
        return $this->setRootTag(false);
    }

    /**
     * Set the root tag for the document.
     *
     * @param string|bool $tag the name to use as the root tag. Set to `false` to disable.
     *
     * @return $this
     */
    public function setRootTag($tag): XMLBuilder
    {
        $this->rootTag = $tag;

        return $this;
    }

    /**
     * Set the data.
     *
     * @param $data
     *
     * @return $this
     */
    public function data(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Handle dynamic setters for `version`, `rootTag`, `itemName` and `encoding`.
     *
     * @param $name
     * @param $arguments
     *
     * @return $this
     */
    public function __call($name, $arguments): XMLBuilder
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

    /**
     * Force item name usage.
     *
     * @param bool $forceItemName
     *
     * @return XMLBuilder
     */
    public function forceItemName(): XMLBuilder
    {
        $this->forceItemName = true;

        return $this;
    }

    /**
     * Make the XML Prolog tag.
     *
     * @return string
     */
    protected function getProlog(): string
    {
        return "<?xml version=\"{$this->version}\" encoding=\"{$this->encoding}\"?>".PHP_EOL;
    }

    /**
     * Make the root tag. Returns `null` if the root tag is disabled.
     *
     * @return null|string
     */
    protected function openRootTag()
    {
        return ! $this->rootTag ? null : "<{$this->rootTag}>";
    }

    /**
     * Make the closing tag for the root tag. Returns `null` if the root tag is disabled.
     *
     * @return null|string
     */
    protected function closeRootTag()
    {
        return ! $this->rootTag ? null : "</{$this->rootTag}>";
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
    protected function getFieldName($field): string
    {
        if (! is_string($field)) {
            return $this->rootTag === self::DEFAULT_ROOT || $this->forceItemName ? $this->itemName : Str::singular($this->rootTag);
        }

        return $field;
    }

    /**
     * Generates the name for fields where the name is a number.
     *
     * If `forceItemName` is enabled this will return the `itemName` config value.
     * Otherwise it will try to use the singular version of $field
     *
     * @param string $field
     *
     * @return string
     */
    protected function generateFieldName(string $field): string
    {
        return $this->forceItemName ? $this->itemName : Str::singular($field);
    }

    /**
     * Check whether the given array is associative or sequential.
     * Returns true if its associative.
     *
     * @param array $array
     *
     * @return bool
     */
    protected function is_assoc(array $array): bool
    {
        // Keys of the array
        $keys = array_keys($array);
        // If the array keys of the keys match the keys, then the array must
        // be associative (e.g. the keys array looked like {0:0, 1:1...}).
        return array_keys($keys) === $keys;
    }
}
