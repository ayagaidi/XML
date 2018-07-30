<?php
 namespace ACFBentveld\XML\Controllers;
 use Illuminate\Support\Facades\View;


 /**
  * This class is depricated and will be removed in the next version
  */
 class XMLBuilder
{
    /**
     * @var string the encoding of the xml document.
     */
    protected $encoding = "UTF-8";
    /**
     * @var string the version of the xml document.
     */
    protected $version = "1.0";
    /**
     * @var null|string the name of the view.
     */
    protected $view = null;
    /**
     * @var array the data to pass to the view.
     */
    protected $viewData = [];
    /**
     * @var string|boolean the name of the root tag. Set to false to disable the root tag.
     */
    protected $rootTag = "root";
     /**
     * XMLBuilder constructor.
     *
     * @param string $encoding the encoding to use for the xml document. Defaults to "UTF-8".
     * @param string $version  the version to use for the xml document. Defaults to "1.0".
     */
    public function __construct(string $encoding = "UTF-8", string $version = "1.0")
    {
        $this->encoding = $encoding;
        $this->version = $version;
    }
     /**
     * Load the view to use.
     *
     * @param string $name the name of the view.
     * @param array  $data optional data to pass to the view.
     *
     * @return $this
     */
    public function loadView(string $name, $data = [])
    {
        $this->view = $name;
        $this->viewData = $data;
        return $this;
    }
     /**
     * Add data to pass to the view.
     *
     * @param mixed $data data to pass to the view.
     *
     * @return $this
     */
    public function with($data)
    {
        if (is_array($this->viewData)) {
            $this->viewData = array_merge($this->viewData, $data);
        }
        $this->viewData = $data;
        return $this;
    }
     /**
     * Disable the root tag.
     *
     * @return $this
     */
    public function disableRootTag()
    {
        return $this->setRootTag(false);
    }
     /**
     * Set the root tag for the document.
     *
     * @param string|boolean $tag the name to use as the root tag. Set to `false` to disable.
     *
     * @return $this
     */
    public function setRootTag($tag)
    {
        $this->rootTag = $tag;
        return $this;
    }
     /**
     * Get the xml as a string.
     *
     * @return string
     */
    public function save()
    {
        return $this->getProlog()
            . $this->openRootTag()
            . View::make($this->view, $this->viewData)->render()
            . $this->closeRootTag();
    }
     /**
     * Make the XML Prolog tag.
     *
     * @return string
     */
    private function getProlog()
    {
        return "<?xml version=\"{$this->version}\" encoding=\"{$this->encoding}\"?>" . PHP_EOL;
    }
     /**
     * Make the root tag. Returns `null` if the root tag is disabled.
     *
     * @return null|string
     */
    private function openRootTag()
    {
        return !$this->rootTag ? null : "<{$this->rootTag}>";
    }
     /**
     * Make the closing tag for the root tag. Returns `null` if the root tag is disabled.
     *
     * @return null|string
     * @author Amando Vledder <amando@acfbentveld.nl>
     */
    private function closeRootTag()
    {
        return !$this->rootTag ? null : "</{$this->rootTag}>";
    }
}