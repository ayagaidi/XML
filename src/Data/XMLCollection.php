<?php

namespace ACFBentveld\XML\Data;

use ACFBentveld\XML\Transformers\PendingTransform;
use ACFBentveld\XML\Transformers\Transformable;
use ArrayAccess;
use ArrayIterator;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use JsonSerializable;

class XMLCollection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    use Transformable;


    private $items;


    public function __construct($items)
    {
        $this->items = (array)$items;
    }


    public function get()
    {
        return $this->applyTransformers($this->items);
    }


    public function collect()
    {
        return new Collection(json_decode(json_encode($this->items)));
    }


    public function __call(string $name, array $arguments)
    {
        return $this->items->{$name}(...$arguments);
    }


    public function __get($key)
    {
        return $this->items->{$key};
    }


    /**
     *
     *
     * @param $key
     *
     * @return \ACFBentveld\XML\Transformers\PendingTransform
     */
    public function transform($key)
    {
        return new PendingTransform($this, function ($transformer) use ($key) {
            $this->items[$key] = is_callable($transformer) ?
                $transformer($this->items[$key])
                : $transformer::apply($this->items[$key]);

            return $this;
        });
    }


    public function expect($key)
    {
        return $this->transform($key);
    }


    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }


    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }


    /**
     * Get an item at a given offset.
     *
     * @param  mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }


    /**
     * Set the item at a given offset.
     *
     * @param  mixed $key
     * @param  mixed $value
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }


    /**
     * Unset the item at a given offset.
     *
     * @param  string $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }


    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }


    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }


    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            }

            return $value;
        }, (array)$this->items);
    }


    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, (array)$this->items);
    }
}