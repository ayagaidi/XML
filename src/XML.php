<?php

namespace ACFBentveld\XML;
use ACFBentveld\XML\Controllers\ExportController;
use ACFBentveld\XML\Controllers\XMLBuilder;
use File;

/**
 * An laravel xml parser package
 *
 */
class XML
{
    
    /**
     * The expactation for the return type
     * By default the return will always be numeric array
     *
     * @var bool
     */
    protected $expect = true;

    /**
     * The file path
     *
     * @var void
     */
    protected $file_path = null;

    /**
     * The xml object raw
     *
     * @var void
     */
    protected $xml = null;

    /**
     * The xml object translated to std object
     *
     * @var void
     */
    protected $xml_object = null;

    /**
     * The optimizing default value
     *
     * @var bool
     */
    protected $optimized = false;

    /**
     * Error handling
     *
     * @var bool
     */
    public $error = false;

    /**
     * Init the text class
     *
     * @param string $path
     * @return \App\Helpers\Text
     */
    public static function path(string $path)
    {
        $class = new Xml;
        $class->file_path = $path;
        $class->readFile();
        return $class;
    }

    /**
     * Create export and save it
     *
     * @param type $function
     * @return void
     */
    public static function export($function = false)
    {
        $class = new ExportController;
        return $class->boot($function);
    }

    /**
     * DEPRICATED FUNCTION !!! Will be removed in next version 1.*
     * THIS FUNCTIONALITY WILL BE MOVED TO export()
     *
     * @param string $encoding
     * @param string $version
     * @return \ACFBentveld\XML\XMLBuilder
     */
    public static function create(string $encoding = "UTF-8", string $version = "1.0")
    {
        return new XMLBuilder($encoding, $version);
    }

    /**
     * Read the xml file
     *
     */
    private function readFile()
    {
        if(File::exists($this->file_path)){
            try{
                $this->xml_object = new \SimpleXMLElement($this->file_path, null, true);
                $this->xml = $this->xml_object;
            }catch(\Exception $e){
                $this->xml = false;
            }
        }
    }

    /**
     * Optimize the xml object
     *
     * @return $this
     */
    public function optimize()
    {
        $this->optimized = $this->loopOptimize($this->xml);
        return $this;
    }

    /**
     * Optimize the opbject. Remove not allowed methods and empty objects
     *
     * @param type $object
     * @param type $new_object
     * @return optimized object
     */
    private function loopOptimize($object)
    {
        $array = (array) $object;
        $data = [];
        foreach($array as $key => $value){
            if(is_object($value)){
                if(strpos(get_class($value),"SimpleXML")!==false){
                    $data[$key] = $this->loopOptimize($value, $data);
                }
            }elseif(is_array($value)){
                $data[$key] = $this->loopOptimize($value, $data);
            }else{
                $data[$this->keyCheck($key)] = $this->typeCheck($value);
            }
        }
        return $data;
    }


    /**
     * check the value type
     *
     * @param type $value
     * @return void
     */
    private function typeCheck($value)
    {
        $p = '/^[0-9]*\.[0-9]+$/';
        if(preg_match($p, $value)){
            return (double) $value;
        }elseif(is_numeric((string)$value)){
            return (int) $value;
        }
        return (string) $value;
    }

    /**
     * Check if the key is valid
     *
     * @param type $key
     * @return void
     */
    private function keyCheck($key)
    {
        $dotreplace     = strtolower(str_replace('.', '_', $key));
        $spacereplace   = str_replace(' ', '_', $dotreplace);
        $dashreplace   = str_replace('-', '_', $spacereplace);
        return $dashreplace;
    }

    /**
     * Return the raw xml object
     *
     * @return \SimpleXMLElement object
     */
    public function raw()
    {
        return $this->xml_object;
    }

    /**
     * Return the xml as a collection
     *
     * @return collection
     */
    public function collect()
    {
        $data = ($this->optimized)?$this->optimized:$this->xml;
        $build = $this->loopCollect($data);
        if(is_object($build) && !isset($build->{0})){
            $object = collect([$build]);
        }else{
            $object = collect($build);
        }
        if(!$this->expect){
            return $object->first();
        }
        return $object;
    }

    /**
     * Loop the data and translate to object/collection
     *
     * @param array $data
     * @return object
     */
    private function loopCollect($data)
    {
        
        $object = [] ;
        foreach($data as $key => $value){
            if(is_array($value) && !is_numeric($key)){
                $object = (object) $this->loopCollect($value);
            }elseif(is_array($value)){
                $object[$key] = (object) $this->loopCollect($value);
            }else{
                $object[$key] =  $value;
            }
            
        }
        return (object)$object;
    }

    /**
     * Set the return expectation
     *
     * @param bool $expect
     * @return $this
     */
    public function expectArray(bool $expect)
    {
        $this->expect = $expect;
        return $this;
    }

    /**
     * Return the complete class
     *
     * @return $this
     */
    public function object()
    {
        return ($this->optimized)?$this->optimized:$this->xml;
    }
}
