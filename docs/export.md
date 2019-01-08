# Laravel XML Reader & Writer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Total Downloads](https://img.shields.io/packagist/dt/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Build Status](https://img.shields.io/travis/ACFBentveld/XML/master.svg?style=flat-square)](https://travis-ci.org/ACFBentveld/XML)
[![StyleCI](https://github.styleci.io/repos/137213815/shield?branch=master)](https://github.styleci.io/repos/137213815)

- [Base Methods](#base-methods)
- [Exporting arrays](#exporting-arrays)
- [Exporting a array without keys](#exporting-a-array-without-keys)
- [Changing the <item> name](#changing-the-item-name)
    - [Using a custom <root> and <item> name.](#using-a-custom-root-and-item-name)
- [Exporting views](#exporting-views)

## Exporting data to XML
An easy way to export your data to XML is using the `XML::export` method. This method can load views or translate arrays to XML.


### Base Methods
Some important methods you need to know about.
* setRootTag(`string $name`) or rootTag(`string $name`) - if you want to change the tag name. Default `export`
* version(`string $version`) - if you want to change the xml version. Default `1.0`
* encoding(`string $encoding`) - if you want to change the xml encoding. Default `UTF-8`
* toString() - if you want to get the xml output as a string
* toFile(`string $path`) - if you want to save the xml to a file directly.
* forceItemName() - if you want to disable default item name generation
* disableRootTag() - if you want to disable the root tag

### Exporting arrays

To export a array to xml you need to use the `export()` method.

```php
$data = [
    'file' => [
        [
            'name' => 'file1',
            'type' => 'pdf',
        ],
        [
            'name' => 'file2',
            'type' => 'png',
        ],
        [
            'name' => 'file3',
            'type' => 'xml',
        ],
    ],
];

$xml = XML::export($data)
    ->toString();
```

This produces the following xml as a string

```xml
<?xml version="1.0" encoding="UTF-8"?>
<files>
  <file>
    <name>file1</name>
    <type>pdf</type>
  </file>
  <file>
    <name>file2</name>
    <type>png</type>
  </file>
  <file>
    <name>file3</name>
    <type>xml</type>
  </file>
</files>
```

If you want to save it as a file simply replace `toString()` with `toFile("/my/path/file.xml")`

### Exporting a array without keys

Version 2 of the package makes it possible to export simple arrays that do not have keys.

```php
$data = [
    'file1',
    'file2',
    'file3',
];

$xml = XML::export($data);

```

Would create

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <item>file1</item>
  <item>file2</item>
  <item>file3</item>
</root>
```

### Changing the <item> name

If you want to change to item name set it using `->itemName($name)`.

```php
$data = [
    'file1',
    'file2',
    'file3',
];

$xml = XML::export($data)
    ->itemName('file');

```

Would create

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <file>file1</file>
  <file>file2</file>
  <file>file3</file>
</root>
```

#### Using a custom <root> and <item> name.

By default the default item name is based on a singular case of the root tag.
If the root tag is "root" we will use "item" as the default item name.

If you are using a custom root tag like "files" we would set the default item name to "file".
To set your own item name use `->itemName($name)` and then `->forceItemName()`.

```php
$data = [
    'file1',
    'file2',
    'file3',
];

$xml = XML::export($data)
    ->rootTag('user_files')
    ->itemName('file');

```

Would create

```xml
<?xml version="1.0" encoding="UTF-8"?>
<user_files>
    <user_file>file1</user_file>
    <user_file>file2</user_file>
    <user_file>file3</user_file>
</user_files>
```

```php
$data = [
    'file1',
    'file2',
    'file3',
];

$xml = XML::export($data)
    ->rootTag('user_files')
    ->itemName('file')
    ->forceItemName();

```

Would create

```xml
<?xml version="1.0" encoding="UTF-8"?>
<user_files>
    <file>file1</file>
    <file>file2</file>
    <file>file3</file>
</user_files>
```


### Exporting views

To export a view simply call `exportView($viewName, $data = [])`

```php

$xml = XML::exportView('my-view', [])
    ->toString();
```