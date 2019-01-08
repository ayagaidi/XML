# Laravel XML made easy!

[![Latest Version on Packagist](https://img.shields.io/packagist/v/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Total Downloads](https://img.shields.io/packagist/dt/acfbentveld/xml.svg?style=flat-square)](https://packagist.org/packages/acfbentveld/xml)
[![Build Status](https://img.shields.io/travis/ACFBentveld/XML/master.svg?style=flat-square)](https://travis-ci.org/ACFBentveld/XML)
[![StyleCI](https://github.styleci.io/repos/137213815/shield?branch=master)](https://github.styleci.io/repos/137213815)

This package is optimized XML handling package for Laravel aiming to be easy and fast.

The main features are

* Fast XML importing with the ability to cast to classes and models
* XML exporting from (nested / value only ) arrays
* Exporting Laravel views to XML

## Installation

You can install the package via composer:

```bash
composer require acfbentveld/xml
```

## Usage
This packages comes with a facade which you can use like this `\XML::` or use it in your class like `use XML;`

In depth guides can be found here:

* [Exporting](https://acfbentveld.github.io/XML/docs/export)
* [Importing](https://acfbentveld.github.io/XML/docs/export)


```php
$notes = XML::import("notes.xml")
    ->cast('note')->to(NoteModel::class)
    ->expect('note')->as('array')
    ->optimize('camelcase')
    ->get();

```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email wim@acfbentveld.nl instead of using the issue tracker.


## Credits

- [Wim Pruiksma](https://github.com/wimurk)
- [Amando Vledder](https://github.com/AmandoVledder)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
