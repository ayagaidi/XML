<?php
require_once 'vendor/autoload.php';

use ACFBentveld\XML\XML;

class Note extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'to',
        'from',
        'heading',
        'body',
        'completed_at'
    ];
}

$path = __DIR__ . '/tests/Features/Import/stubs/notes.xml';

$xml = XML::import($path)
    //->cast('note')->to(Note::class)
    //->expect('note')->as('array')
    //->optimize('camelcase')
    ->get();

dd($xml->note->completed_at != "");

//
//$xml = XML::import($path)
//    ->collect();
//
//dump($xml);