<?php


namespace ACFBentveld\XML;


use ACFBentveld\XML\Data\XMLCollection;
use ACFBentveld\XML\Data\XMLElement;
use Exception;
use Illuminate\Support\Facades\File;
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
     * @var SimpleXMLElement|mixed the processed xml ready to be used
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
        if (File::exists($this->path)) {
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
     * @return mixed|\ACFBentveld\XML\XMLElement
     */
    public function get()
    {
        return $this->output;
    }
}