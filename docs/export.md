# Laravel XML Reader & Writer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Total Downloads](https://img.shields.io/packagist/dt/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)

## Exporting data to XML
An easy way to export your data to XML is using the `XML::export` method. This method can load views or translate collections to XML.

Checkout the import methods
* [Import XML](https://acfbentveld.github.io/XML/)


### Methods
Some important methods you need to know about.
* setName(`string $name`) if you want to change the tag name. Default `export`
* setVersion(`string $version`) if you want to change the xml version. Default `1.0`
* setIso(`string $iso`) if you want to change the xml ISO. Default `UTF-8`
* setType(`string $type`) if you want to change the XML type. Default `xml`

```php
    $preview = XML::export()->setName('export')->setVersion('1.0')->setIso('UTF-8')->setType('xml')->export();
```

### Simple export
Just a simple sample for exporting data to XML. Also in this sample we will use an atribute. Every attribute contains the `:` character. 

```php
    
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
        return $data; //or do domething funny here. We will just return it here.
    })
    ->setName('Red Wedding') // this is the elements name
    ->export(storage_path('app/media')); //or use saveAs to set a name
    
```
Above will create an XML document that looks like this : 
```xml
<?xml version="1.0" encoding="UTF-8"?>
<xml>
    <red-wedding>
        <john>snow</john>
        <knows>nothing</knows>
    </red-wedding>
    <red-wedding>
        <dragons>are</dragons>
        <!-- there it is. The attribute-->
        <awesome arent:they="yes they are" /> 
    </red-wedding>
</xml>
```

### Exporting collections 
Exporting collections works as simpel as the default export method. Init the facade. Call the method en done.
Trust me i know something.

```php
    $collection = User::all();
    XML::export()->loadCollection($collection)->export('red_wedding_guys.xml'); //yes you can pass the name to this method also
```
Thats it. The export method will create a nice and neat xml file.

### Loading views (magic)
Yes, this is where the dragons become real. Loading a view and translate it to XML. It can't get any easier. 

Lets call the `loadView` method and get started
```php
$users = User::all();
XML::export()->loadView('users.export', compact('users'))->export(storage_path('red_wedding_members.xml'));
```

Create a view and insert a table like below:
```html
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>\
            <th>Memo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->memo}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
```
The above code will output an XML like 
```xml
<?xml version="1.0" encoding="UTF-8"?>
<xml>
    <export>
        <name>Lord Walder</name>
        <email>waldy@casterlyrock.com</email>
        <memo>Anoying person</memo>
    </export>
    <export>
        <name>Rob Stark</name>
        <email>robert@starkindustries.com</email>
        <memo>Can't come. Got headache</memo>
    </export>
</xml>

```
