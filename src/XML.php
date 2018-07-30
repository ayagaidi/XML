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

    protected $file_path;
    protected $xml;
    protected $xml_object;
    protected $optimized;
    public $error;

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
    private function loopOptimize($obj)
    {
        $data = new \stdClass();
        if ((is_object($obj) && get_class($obj) == 'SimpleXMLElement')) {
            if (count($obj->children())) {
                $data = $this->loopOptimizedChildren($data, $obj);
            }
            if (count($obj->attributes())) {
                $data = $this->loopOptimizedAttributes($data, $obj);
            }
            if (count(get_object_vars($data)) == 0) {
                $data = $this->typeCheck($obj);
            } elseif (strlen((string) $obj)) {
                $data->value = $this->typeCheck($obj);
            }
        } elseif (is_array($obj)) {
            $data = $this->loopOptimizedArray($data, $obj);
        } else {
            $data = $this->typeCheck($obj);
        }
        return $data;
    }

    /**
     * If this is actually an array, treat it as such.
     * This sort of thing is what makes simpleXML a pain to use.
     * 
     */
    private function loopOptimizedChildren($data, $obj)
    {
        foreach ($obj as $key => $value) {
            if (count($obj->$key) > 1) {
                if (!isset($data->$key) || !is_array($data->$key)) {
                    $data->{$this->keyCheck($key)} = array();
                }
                array_push($data->{$this->keyCheck($key)}, $this->loopOptimize($value));
            } else {
                $data->{$this->keyCheck($key)} = $this->loopOptimize($value);
            }
        }
        return $data;
    }

    /**
     * Loop through the attributes
     *
     * @param type $data
     * @param type $obj
     * @return stdClass
     */
    private function loopOptimizedAttributes($data, $obj)
    {
        foreach ($obj->attributes() as $key => $value) {
            $data->{$this->keyCheck($key)} = $this->typeCheck($value);
        }
        return $data;
    }

    /**
     * Loop through the xml array
     *
     * @param type $data
     * @param type $obj
     * @return stdClass
     */
    private function loopOptimizedArray($data, $obj)
    {
        foreach ($obj as $key => $value) {
            $data->$key = $this->loopOptimize($value);
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
        $object = ($this->optimized)?$this->optimized:$this->xml;
        foreach($object as $key => $item){
            if(is_object($object)){
                $object->$key = collect($item);
            }else{
                $object[$key] = collect($item);
            }
        }
        return $object;
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
