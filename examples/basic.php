<?php
require_once '../vendor/autoload.php';


use ACFBentveld\XML\XML;

$notes = XML::import('notes.xml')->get();

// Use the xml here... e.g $notes->attribute('count');
