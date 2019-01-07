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
    ->cast('note')->to(Note::class)
    ->expect('note')->as('array')
    ->get();

dump($xml);
//
//$xml = XML::import($path)
//    ->collect();
//
//dump($xml);