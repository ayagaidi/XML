<?php

use ACFBentveld\XML\XML;

require_once "vendor/autoload.php";

$data = array(
    0 => array(
        'john' => 'snow',
        'knows' => 'nothing'
    ),
    1 => array(
        'dragons' => 'are',
        'awesome arent:they' => "yes they are" //lets use an attribute in here
    )
);

XML::export(function() use ($data){
    return $data;
})->setName('Red Wedding')->setRootTag('test')->export('test.xml');