Ddeboer Data Import Bundle
==========================

Introduction
------------
This is a bundle for the [ddeboer/data-import library](https://github.com/ddeboer/data-import).

Installation
------------

This library is available on [Packagist](http://packagist.org/packages/ddeboer/data-import-bundle):

To install it, add the following to your `composer.json`:

```
"require": {
    ...
    "ddeboer/data-import-bundle": "dev-master",
    ...
}
```

And run `$ php composer.phar install`.

For Symfony 2.1, use branch `symfony-2.1` instead:

```
"require": {
    ...
    "ddeboer/data-import-bundle": "symfony-2.1",
    ...
}
```

Then add the bundle to `app/AppKernel.php`:

```
public function registerBundles()
{
    return array(
        ...
        new Ddeboer\DataImportBundle\DdeboerDataImportBundle(),
        ...
    );
}
```