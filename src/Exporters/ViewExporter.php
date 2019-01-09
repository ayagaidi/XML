<?php

namespace ACFBentveld\XML\Exporters;

use ACFBentveld\XML\XMLBuilder;

class ViewExporter extends XMLBuilder implements Exporter
{
    /**
     * ViewExporter constructor.
     *
     * @param string $viewName - name of the view
     * @param mixed  $data     - data to pass to the view
     */
    public function __construct(string $viewName, $data = [])
    {
        parent::__construct();

        $this->data = view($viewName)->with($data)->render();
    }

    /**
     * Get the xml as a string.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->getProlog()
            .$this->openRootTag()
            .$this->data
            .$this->closeRootTag();
    }


    /**
     * Save the xml to a file.
     *
     * @param string $path - the path to the file
     */
    public function toFile(string $path)
    {
        \File::put($path, $this->toString());
    }
}
