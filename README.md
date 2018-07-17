# Laravel XML reader

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Total Downloads](https://img.shields.io/packagist/dt/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)

This reader contains an xml reader for laravel. This package can read xml files into objects or collections wich makes it easy to edit the xml data. It also comes with a optimize function to restore broken data or to fix keys that are not allowed to use in PHP. 

## Installation

You can install the package via composer:

```bash
composer require acfbentveld/xml
```

## Usage
This packages comes with a facade. You can use the package like this `\XML::` or use it in your class like `use XML;`

### Read xml file
To read an xml file, it must be stored on the server. Call the file using the full path.
use the `path()` function to read the xml file. 
```php
    $path = storage_path().'/my-xml.xml';
    $xml = XML::path($path);
``` 

## Debug
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

# Generating XML documents using Blade templates

## Changing the encoding / version
You can change the encoding / version of the xml document by passing them to the `create` method

```php
$xml = XML::create($encoding = "UTF-8", $version = "1.0");
```

## Loading views
To load a view you can use
```php
$xml = XML::create()->loadView('view.name', $data);
```

or

```php
$xml = XML::create()->loadView('view.name')->with($data);
```

## Customizing the root tag
To customize the root tag you can use the `setRootTag` method

```php
$xml->setRootTag("my-root-tag");
```

to disable the root tag you need to set the root tag to `false` or you can use the `disableRootTag` helper

```php
$xml->disableRootTag();
```

## Getting the generated XML
To get the generated xml you need to call the `save` method. This will return a string with the generated XML

```php
$xml->save();
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email wim@acfbentveld.nl instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: ACF Bentveld, Ecu 2 8305 BA, Emmeloord, Netherlands.

## Credits

- [Wim Pruiksma](https://github.com/wimurk)
- [Amando Vledder](https://github.com/AmandoVledder)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
