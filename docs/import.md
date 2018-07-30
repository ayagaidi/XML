# Laravel XML Reader & Writer

### Read xml file
To read an xml file, it must be stored on the server. Call the file using the full path.
use the `path()` function to read the xml file. 
```php
    $path = storage_path().'/my-xml.xml';
    $xml = XML::path($path);
``` 

### Debug
Sometimes your xml file is not readable or does not exists int he path you pass to the XML reader.
Well we don't want your whole page to blow up while you are developing. Use the `->debug()` to get a list of all erorrs.
```php
    $path = storage_path().'/my-xml.xml';
    $xml = XML::path($path)->debug();  //returns an array with errors if there are any
```


### Optimize
Sometimes the exported xml has broken keys or you just don't like the idea that empty values are translated as objects in simpleXMl. Use the `optimze()` function to optimize the data for PHP. It repairs the keys and sets empty values as `NULL`.
```php
    $path = storage_path().'/my-xml.xml';
    $xml = XML::path($path)->optimize();
```

#### Convert XML data
The xml data can be exported to a few formats. 
* Object
* Collection object
* Raw (SimpleXMlObject)

#### object
Export the data as an object (optimize is optional but recomended)
```php
    $path = storage_path().'/my-xml.xml';
    $xml = XML::path($path)->optimize()->object();
    dd($xml);
```

#### collection
Export the data as a collection (optimize is optional but recomended)
```php
    $path = storage_path().'/my-xml.xml';
    $xml = XML::path($path)->optimize()->collect();
    dd($xml);
```

#### raw SimpleXML
Export the data as a SimpleXMLObject (optimize won't work with the function `raw` since it is a raw export)
```php
    $path = storage_path().'/my-xml.xml';
    $xml = XML::path($path)->raw();
    dd($xml);
```