# Laravel XML Reader & Writer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Total Downloads](https://img.shields.io/packagist/dt/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)

> This functionality is moved to [exports](https://acfbentveld.github.io/XML/)

## Generating XML documents using Blade templates

### Changing the encoding / version
You can change the encoding / version of the xml document by passing them to the `create` method

```php
$xml = XML::create($encoding = "UTF-8", $version = "1.0");
```

### Loading views
To load a view you can use
```php
$xml = XML::create()->loadView('view.name', $data);
```

or

```php
$xml = XML::create()->loadView('view.name')->with($data);
```

### Customizing the root tag
To customize the root tag you can use the `setRootTag` method

```php
$xml->setRootTag("my-root-tag");
```

to disable the root tag you need to set the root tag to `false` or you can use the `disableRootTag` helper

```php
$xml->disableRootTag();
```

### Getting the generated XML
To get the generated xml you need to call the `save` method. This will return a string with the generated XML

```php
$xml->save();
```