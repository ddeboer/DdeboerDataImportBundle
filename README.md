[![Build Status](https://secure.travis-ci.org/ddeboer/DdeboerDataImportBundle.png)](https://secure.travis-ci.org/ddeboer/DdeboerDataImportBundle.png)

Ddeboer Data Import Bundle
==========================

Introduction
------------
This Symfony2 bundle offers a way to import data from, and store data to, a
range of formats and media.

Installation
------------

1. Add the following to your `deps` file:

```
[DdeboerDataImportBundle]
    git=https://github.com/ddeboer/DdeboerDataImportBundle
    target=/bundles/Ddeboer/DataImportBundle
```

And run the vendors script from your project’s directory:

```
bin/vendors install
```

2. Add the Ddeboer namespace `app/autoload.php`:

```
$loader->registerNamespace(array(
    ...
    'Ddeboer'   => __DIR__.'/../vendor/bundles',
    ...
));
```

3. Add the bundle to `app/AppKernel.php`:

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

Usage
-----

1. Create an `splFileObject` from the source data. You can also use a `source`
   object to retrieve this `splFileObject`: construct a source and add `source
   filters`, if you like.
2. Construct a `reader` object and pass an `splFileObject` to it.
3. Construct a `workflow` object and pass the reader to it. Add at least one 
   `writer` object to this workflow. You can also add `filters` and `converters`
   to the workflow.
4. Process the workflow: this will read the data from the reader, filter and
   convert the data, and write it to the writer(s).

An example:

```
use Ddeboer\DataImportBundle\Source\Http;
use Ddeboer\DataImportBundle\Source\Filter\Unzip;
use Ddeboer\DataImportBundle\Reader\CsvReader;
use Ddeboer\DataImportBundle\Workflow;
use Ddeboer\DataImportBundle\Converter\DateTimeConverter;

(...)

// Create the source; here we use an HTTP one
$source = new Http('http://www.opta.nl/download/registers/nummers_csv.zip');

// As the source file is zipped, we add an unzip filter
$source->addFilter(new Unzip('nummers.csv'));

// Retrieve the \SplFileObject from the source
$file = $source->getFile();

// Create and configure the reader
$csvReader = new CsvReader($file);
$csvReader->setHeaderRowNumber(0);

// Create the workflow
$workflow = new Workflow($csvReader);
$dateTimeConverter = new DateTimeConverter();

// Add converters to the workflow
$workflow->addConverter('twn_datumbeschikking', $dateTimeConverter)
         ->addConverter('twn_datumeind', $dateTimeConverter)
         ->addConverter('datummutatie', $dateTimeConverter)

// You can also add closures as converters
         ->addConverterClosure('twn_nummertm', function($input) {
             return str_replace('-', '', $input);
         })
         ->addConverterClosure('twn_nummervan', function($input) {
             return str_replace('-', '', $input);
         })

// For now, no writers are supplied yet, so implement your own or use a closure
        ->addWriterClosure(function($csvLine) {
            var_dump($csvLine);
        });

// Process the workflow
$workflow->process();
```