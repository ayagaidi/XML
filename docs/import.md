# Laravel XML Reader & Writer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Total Downloads](https://img.shields.io/packagist/dt/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Build Status](https://img.shields.io/travis/ACFBentveld/XML/master.svg?style=flat-square)](https://travis-ci.org/ACFBentveld/XML)
[![StyleCI](https://github.styleci.io/repos/137213815/shield?branch=master)](https://github.styleci.io/repos/137213815)

- [Base Methods](#base-methods)
- [Using the xml results.](#using-the-xml-results)
- [Importing a file](#importing-a-file)
    - [Getting the raw xml](#getting-the-raw-xml)
- [Casting](#casting)
- [Transforming](#transforming)
- [Optimizing](#optimizing)


## Importing XML

An easy way to import your data is by using the `XML::import` method. This method can load xml by path or by url.


### Base Methods
Some important methods you need to know about.
* toJson() - if you want to get the xml as a JSON string
* toArray() - if you want to get the xml as a array.
* collect() - if you want to get the xml as a collection.


### Using the xml results.

The xml package returns `XMLObject` and `XMLCollection` objects.

Both of these classes have the same api.

To get a simple value by key just access it as a object or array.

To get a attribute value use `->attribute($name, $defaultValue = null)`

To check if a attribute exists use `->hasAttribute($name)`

For more information and examples on the basics see the [examples/basic.php](/examples/basic.php) file.

### Importing a file

To import xml simply call the `import` method with a path to the xml.

```php
$xml = XML::import($path)->get();
```

This will give you a `XMLObject` containing your xml.

#### Getting the raw xml

If you want the raw xml replace `->get()` with `->raw()`


### Casting

When importing XML it is often useful to cast you xml directly to class Laravel model. Lucky for you our package makes that super simple.
To cast a xml item simple add `->cast($name)->to($class)`

```php
XML::import($path)
    ->cast('my_item')->to(MyModel::class)
```

By default we check if the passed class is a Laravel model, if so we create a new model instance and fill it using the xml without saving it.
To save it to the database add the `->save()` calls yourself.

If the class is not a laravel model we pass a array with data to the constructor.

If you want to change this behavior apply the `ACFBentveld\XML\Casts\Castable` interface on your class.

For more information and examples on casting checkout the [examples/casts.php](/examples/casts.php) file.


### Transforming

Transforming is simple way to transform data before using it.

A good example is this xml.

Lets say we load the following xml and then try to loop trough it.

```xml
<data>
    <note completed="true">
        <to.user>test</to.user>
        <to attr="test">Foo</to>
        <from>Bar</from>
        <heading>Baz</heading>
        <body>FooBar!</body>
        <created_at/>
        <updated_at/>
        <completed_at>01-01-1970 12:00</completed_at>
    </note>
    <note completed="true">
        <to.user>test</to.user>
        <to attr="test">Foo</to>
        <from>Bar</from>
        <heading>Baz</heading>
        <body>FooBar!</body>
        <created_at/>
        <updated_at/>
        <completed_at>01-01-1970 12:00</completed_at>
    </note>
</data>
```

And then we would loop trough it like this

```php
$notes = XML::import('notes.xml')
    ->toArray();
    
foreach($notes->note as $note) { 
    // ... 
}
```

But what if we suddenly only have 1 `<note>`

```xml
<data>
    <note completed="true">
        <to.user>test</to.user>
        <to attr="test">Foo</to>
        <from>Bar</from>
        <heading>Baz</heading>
        <body>FooBar!</body>
        <created_at/>
        <updated_at/>
        <completed_at>01-01-1970 12:00</completed_at>
    </note>
</data>
```

Now the foreach would we looping over the children of the note instead of over all the notes.

How do we fix this?

We tell the import to always give us 'note' as a array.

```php
$notes = XML::import('notes.xml')
    ->transform('note')->with(ArrayTransformer::class)
    ->toArray();
```

Behind the scenes the import looks up the ArrayTransformer.
In our case this just performs a `array_wrap()` on `note`.

But this doesnt look that nice yet.

So lets use

```php
->expect('note')->as('array')
```

> `expect()` is just a alias for `transform()` and `as()` is a alias for `with()` that takes a class alias

For more information and examples on transforming checkout the [examples/transformers.php](/examples/transformers.php) file.

### Optimizing

Some times you have xml that you cannot change that contains weird keys.

```xml
<to.user>foobar</to.user>
```

for example. These cannot be accessed by `->to.user` which can be annoying.

Therefore we made a optimize function. By default it just replaces all `spaces`, `dots` and `-` in the xml tags with a `_`.

```php
$notes = XML::import('notes.xml')
    ->optmize()
    ->toArray();
```

To you want illegal tags the become `camelCase` pass `'camelcase'` to the optimize function.

```php

// notes.xml :
// <to.user>foobar</to.user>

$notes = XML::import('notes.xml')
    ->optmize()
    ->toArray(); // to_user = foobar
    
$notes = XML::import('notes.xml')
    ->optmize('camelcase')
    ->toArray(); // toUser = foobar

```