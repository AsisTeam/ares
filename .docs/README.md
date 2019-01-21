# ARES - economic subject finder

This API wrapper allows you to get basic information about companies by their ID or list companies by given name. 

## Client

In order to use this api wrapper you need to instantiate a `AsisTeam\ARES\Client\Finder`.

The simplest client creation is following
```php
$finder = new Finder();
``` 

### List of available methods
- findById(string $companyId): ?Subject
- findByName(string $name): Subject[]

#### Subject entity

When some economic subject matches, this api wrapper returns the `Subject` instance.
Methods:
- `getCompanyID` (returns ico [string])
- `getCompanyName` (returns name [string])
- `getAddress` (returns address [string])
- `getVatId` (returns vat ID [string])
- `getLegalFormName` (returns name of the legal form [string])
- `isPhysicalPerson` (returns whether subject is physical person/individual [bool])
- `isLegalPerson` (returns whether subject is legal person/company etc. [bool])

#### Exceptions

In case of any invalid given data, server error or other error `RequestException` or `ResponseException` (or their child exceptions) are thrown.
If you're searching by company name using `findByName` method you dan face `TooManySubjectsException` that extends `ResponseException` and means that the given $name is too general.

### Usage

```php
$finder = new Finder();
$subject = $finder->findById($ic = '28319061');

Assert::equal($ic, $subject->getCompanyId());
Assert::equal('28319061', $subject->getVatId());
Assert::equal('AsisTeam s.r.o.', $subject->getName());
Assert::equal('Praha 1, Staré Město, Kaprova 42/14', $subject->getAddress());
Assert::equal(112, $subject->getLegalForm());
Assert::equal('Společnost s ručením omezeným', $subject->getLegalFormName());
Assert::true($subject->isLegalPerson());
Assert::false($subject->isPhysicalPerson());

// ----------------------------------

$finder = new Finder();
Assert::null($finder->findById($ic = '98765432')); // 98765432 is non-existing ico

// ----------------------------------

$finder = new Finder();
$subjects = $finder->findByName('Asis'); 
Assert::count(5, $subjects); // 5 subjects match given "Asis" name
```

## Nette

You can setup package as Nette compiler extension using neon config
Extension will create all client factories as services

### Usage

```neon
extensions:
    ares: AsisTeam\ARES\DI\AresExtension

ares:
    # max request duration
    timeout: 10
```
