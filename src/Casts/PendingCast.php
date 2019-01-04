<?php


namespace ACFBentveld\XML\Casts;


class PendingCast
{
    private $context;
    /**
     * @var \Closure
     */
    private $resolve;


    /**
     * Create a new PendingTransform.
     *
     * @param          $context
     * @param \Closure $resolve
     */
    public function __construct($context, \Closure $resolve)
    {
        $this->context = $context;
        $this->resolve = $resolve;
    }


    /**
     * Transform and resolve using a transformer.
     *
     * @param $transformer
     *
     * @return mixed
     */
    public function to($cast)
    {
        $resolve = $this->resolve;

        return $resolve($cast);
    }
}