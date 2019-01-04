<?php

namespace ACFBentveld\XML\Transformers;

trait Transformable
{
    /**
     * @var \ACFBentveld\XML\Transformers\Transformer[] list of all transformers to apply
     */
    protected $transformers = [];


    /**
     * Add a transformer to the output
     *
     * @param \ACFBentveld\XML\Transformers\Transformer|string $transformer
     *
     * @return $this
     */
    public function addTransformer($transformer)
    {
        $this->transformers[] = $transformer;
        return $this;
    }


    /**
     * Apply the registered transformers on the input
     *
     * @param mixed $on - input to apply the transformers on
     *
     * @return mixed - the transformed input
     */
    private function applyTransformers($on)
    {
        foreach ($this->getTransformers() as $transformer) {
            $on = $transformer::apply($on);
        }
        return $on;
    }


    /**
     * Get the transformers
     *
     * @return \ACFBentveld\XML\Transformers\Transformer[]
     */
    public function getTransformers(): array
    {
        return $this->transformers;
    }
}