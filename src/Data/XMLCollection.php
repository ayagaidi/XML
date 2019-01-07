<?php

namespace ACFBentveld\XML\Data;

use Countable;
use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;
use ACFBentveld\XML\Casts\Cast;
use Illuminate\Support\Collection;
use ACFBentveld\XML\Casts\PendingCast;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use ACFBentveld\XML\Transformers\Transformable;
use ACFBentveld\XML\Transformers\PendingTransform;

class XMLCollection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    use Transformable;

    /**
     * @var array
     */
    private $items;

    /**
     * XMLCollection constructor.
     *
     * @param $items
     */
    public function __construct($items)
    {
        $this->items = (array) $items;
    }

    /**
     * Get the xml.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->applyTransformers($this->items);
    }

    /**
     * Get the xml as a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collect()
    {
        return new Collection(json_decode(json_encode($this->items)));
    }

    /**
     * Pass overloaded methods to the items.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->items->{$name}(...$arguments);
    }

    /**
     * Get a item from the xml.
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->items->{$key};
    }

    /**
     * Start a transform for the given key.
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

    /**
     * Alias for transform.
     *
     * @see transform
     */
    public function expect($key)
    {
        return $this->transform($key);
    }

    /**
     * Start a cast for the given key.
     *
     * @param $key
     *
     * @return \ACFBentveld\XML\Casts\PendingCast
     */
    public function cast($key)
    {
        return new PendingCast($this, function ($cast) use ($key) {
            $this->items[$key] = Cast::to((array) $this->items[$key], $cast);

            return $this;
        });
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
        }, (array) $this->get());
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
        }, (array) $this->get());
    }
}
