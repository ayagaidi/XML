<?php


namespace ACFBentveld\XML;


use ACFBentveld\XML\Data\XMLCollection;
use ACFBentveld\XML\Data\XMLElement;
use Exception;
use SimpleXMLElement;

class XMLImporter
{
    /**
     * @var SimpleXMLElement the loaded xml
     */
    public $xml;
    /**
     * @var string path of the xml file to load
     */
    protected $path;
    /**
     * @var XMLCollection the processed xml ready to be used
     */
    protected $output;


    /**
     * XMLImporter constructor.
     *
     * @param string $path - path of the xml file to load
     *
     * @throws \Exception
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->load();
    }


    /**
     * Load the xml
     *
     * @throws \Exception
     */
    private function load()
    {
        //if (File::exists($this->path)) {
        if (true) {
            try {
                $this->xml = new XMLElement($this->path, null, true);
                $this->output = new XMLCollection($this->xml);
            } catch (Exception $exception) {
                throw $exception;
            }
        }
    }


    /**
     * Get the loaded xml
     *
     * @return XMLCollection
     */
    public function get()
    {
        return $this->output;
    }

}