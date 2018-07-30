<?php

namespace ACFBentveld\XML\Controllers;


/**
 * An laravel xml parser package
 *
 */
class ExportController
{

    protected $version = '1.0';
    protected $iso = 'UTF-8';
    protected $name   = 'export';
    protected $type = 'xml';
    protected $fields = [];
    protected $collection = false;
    protected $view;
    protected $data = [];

    /**
     * Build the export controller
     *
     * @param type $function
     * @return $this
     */
    public function boot($function = false)
    {
        $this->fields = ($function) ? $function() : [];
        return $this;
    }

    /**
     * Change the class name property
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Change the version
     *
     * @param string $verson
     * @return $this
     */
    public function setVersion(string $verson)
    {
        $this->version = $verson;
        return $this;
    }

    /**
     * change the iso
     *
     * @param string $iso
     * @return $this
     */
    public function setIso(string $iso)
    {
        $this->iso = $iso;
        return $this;
    }

    /**
     * Change the type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Load collection
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @return $this
     */
    public function loadCollection(\Illuminate\Database\Eloquent\Collection $collection)
    {
        $this->collection = true;
        $this->name = ($this->name === 'export') ? class_basename($collection) : $this->name;
        $this->fields = $collection;
        return $this;
    }

    /**
     * Load view and translate it to array
     *
     * @param string $view
     * @param type $data
     * @return string
     */
    public function loadView(string $view, $data)
    {
        $html = view($view)->with($data)->render();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $headers = $dom->getElementsByTagName('th');
        $body = $dom->getElementsByTagName('td');
        $heads = [];
        foreach($headers as $header){
            $heads[] = $header->textContent;
        }
        $i = -1;$b = 0;
        foreach($body as $key => $value){$i++;
            if($i === $headers->length){
                $i = 0;$b++;
            }
            $this->fields[$b][$heads[$i]] = $value->textContent;
        }
        return $this;
    }

    

    /**
     * Create xml file and save it as the class name property
     *
     * @param string $path
     * @return boolean
     */
    public function export(string $path)
    {
        if($this->view){
           $this->translateView();
        }
        if(!str_contains($path, '.xml')){
            $path .= '/'.strtolower(str_slug($this->name)).'.xml';
        }
        $this->xml = $this->createXMl();
        $this->xml->save($path);
        return true;
    }

    /**
     * Create xml document and rename the file to the given parameter
     *
     * @param string $path
     * @param string $name
     * @return boolean
     */
    public function exportAs(string $path, string $name)
    {
        if($this->view){
           $this->translateView();
        }
        if(!str_contains($name, ['xml', 'xmls'])){
            $name .= str_slug($name.'.xml');
        }
        if(!str_contains($path, '.xml')){
            $path .= '/'.strtolower($name);
        }
        $this->xml = $this->createXMl();
        $this->xml->save($path);
        return true;
    }

    /**
     * Create the document.
     *
     * @return \DOMDocument
     */
    protected function createXMl()
    {
        /* create a dom document with encoding utf8 */
        $domtree = new \DOMDocument($this->version, $this->iso);
        /* create the root element of the xml tree */
        $xmlRoot = $domtree->createElement($this->type);
        /* append it to the document created */
        $root = $domtree->appendChild($xmlRoot);
        foreach($this->fields as $fields){
            $this->name = ($this->collection && $this->name === class_basename($this->fields)) ? class_basename($fields) : $this->name;
            $currentRow = $domtree->createElement(strtolower(str_slug($this->name)));
            $row = $root->appendChild($currentRow);
            $fields = ($this->collection) ? $fields->toArray() : $fields;
            foreach($fields as $key => $value){
                if(str_contains($key, [':'])){
                    $node = $this->createAttributeNode($domtree, $key, $value);
                }else{
                    $node = $domtree->createElement(str_slug($key), $value);
                }
                $row->appendChild($node);
            }
        }
        return $domtree;
    }

    /**
     * Create element with attribute
     *
     * @param \DOMDocument $domtree
     * @param void $key
     * @param void $value
     * @return \DOMDocument $node
     */
    private function createAttributeNode($domtree, $key, $value)
    {
        $explode = explode(' ', $key);
        if (isset($explode[0]) && isset($explode[1])) {
            $node = $domtree->createElement($explode[0]);
            $node->setAttribute($explode[1], $value);
        }
        return $node;
    }
}