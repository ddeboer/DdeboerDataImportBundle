Ddeboer Data Import Bundle
==========================

Introduction
------------
This is a Symfony2 bundle for the [ddeboer/data-import library](https://github.com/ddeboer/data-import).

Installation
------------

This library is available on [Packagist](http://packagist.org/packages/ddeboer/data-import-bundle):

To install it, run: 

    $ composer require ddeboer/data-import-bundle:~0.1

If you’re on Symfony 2.1, use the appropriate branch instead:

    $ composer require ddeboer/data-import-bundle:symfony-2.1

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
